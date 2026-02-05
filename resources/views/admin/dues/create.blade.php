@extends('admin.layouts.app')

@section('title', 'Yeni Aidat Oluştur')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-plus mr-2 text-green-500"></i>
                Yeni Aidat Oluştur
            </h1>
            <p class="mt-2 text-gray-600">Yeni bir aidat kaydı oluşturun.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.dues.index') }}" class="btn-secondary px-6 py-3 rounded-xl font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.dues.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Member Information -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Üye Bilgileri
                </h3>

                <div>
                    <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-users mr-2 text-green-500"></i>
                        Üye *
                    </label>
                    <select name="member_id" id="member_id" required
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('member_id') border-red-500 @enderror">
                        <option value="">Üye seçin</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }} ({{ $member->email }}) - {{ $member->monthly_dues }}€/ay
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Due Information -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                    Aidat Bilgileri
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-purple-500"></i>
                            Yıl *
                        </label>
                        <select name="year" id="year" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year') border-red-500 @enderror">
                            <option value="">Yıl seçin</option>
                            @for($year = 2020; $year <= 2030; $year++)
                                <option value="{{ $year }}" {{ old('year', now()->year) == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-day mr-2 text-orange-500"></i>
                            Ay *
                        </label>
                        <select name="month" id="month" required
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('month') border-red-500 @enderror">
                            <option value="">Ay seçin</option>
                            @foreach([
                                1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                                5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                                9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                            ] as $monthNum => $monthName)
                                <option value="{{ $monthNum }}" {{ old('month', now()->month) == $monthNum ? 'selected' : '' }}>
                                    {{ $monthName }}
                                </option>
                            @endforeach
                        </select>
                        @error('month')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave mr-2 text-green-500"></i>
                            Tutar (€) *
                        </label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-clock mr-2 text-red-500"></i>
                        Son Ödeme Tarihi *
                    </label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                    Notlar
                </label>
                <textarea name="notes" id="notes" rows="4"
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                          placeholder="Aidat hakkında ek notlar...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.dues.index') }}" class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Oluştur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
