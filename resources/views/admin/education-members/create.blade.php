@extends('admin.layouts.app')

@section('title', 'Yeni Veli Ekle')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">
                        <i class="fas fa-user-plus text-blue-600 mr-3"></i>
                        Yeni Veli Ekle
                    </h1>
                    <p class="text-slate-600">Veli bilgilerini girin</p>
                </div>
                <a href="{{ route('admin.education-members.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Geri Dön
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <form action="{{ route('admin.education-members.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Temel Bilgiler -->
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Temel Bilgiler</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Veli Adı -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                Veli Adı <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="Veli Adı"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Veli Soyadı -->
                        <div>
                            <label for="surname" class="block text-sm font-medium text-slate-700 mb-2">
                                Veli Soyadı <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="surname"
                                   id="surname"
                                   value="{{ old('surname') }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('surname') border-red-500 @enderror"
                                   placeholder="Veli Soyadı"
                                   required>
                            @error('surname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Öğrenci Adı -->
                        <div>
                            <label for="student_name" class="block text-sm font-medium text-slate-700 mb-2">
                                Öğrenci Adı <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="student_name"
                                   id="student_name"
                                   value="{{ old('student_name') }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('student_name') border-red-500 @enderror"
                                   placeholder="Öğrenci Adı"
                                   required>
                            @error('student_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Öğrenci Soyadı -->
                        <div>
                            <label for="student_surname" class="block text-sm font-medium text-slate-700 mb-2">
                                Öğrenci Soyadı <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="student_surname"
                                   id="student_surname"
                                   value="{{ old('student_surname') }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('student_surname') border-red-500 @enderror"
                                   placeholder="Öğrenci Soyadı"
                                   required>
                            @error('student_surname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Durum -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-2">
                                Durum <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                <option value="">Durum Seçin</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- İletişim Bilgileri -->
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">İletişim Bilgileri</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- E-posta -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                                E-posta
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   placeholder="ornek@email.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">
                                Telefon
                            </label>
                            <input type="tel"
                                   name="phone"
                                   id="phone"
                                   value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                                   placeholder="0555 123 45 67">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


                <!-- Üyelik ve Aidat Bilgileri -->
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Üyelik ve Aidat Bilgileri</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Üyelik Tarihi -->
                        <div>
                            <label for="membership_date" class="block text-sm font-medium text-slate-700 mb-2">
                                Üyelik Tarihi <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   name="membership_date"
                                   id="membership_date"
                                   value="{{ old('membership_date', now()->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('membership_date') border-red-500 @enderror"
                                   required>
                            @error('membership_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Aylık Aidat -->
                        <div>
                            <label for="monthly_dues" class="block text-sm font-medium text-slate-700 mb-2">
                                Aylık Aidat (€) <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   name="monthly_dues"
                                   id="monthly_dues"
                                   value="{{ old('monthly_dues', 0) }}"
                                   min="0"
                                   step="0.01"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('monthly_dues') border-red-500 @enderror"
                                   placeholder="0.00"
                                   required>
                            @error('monthly_dues')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notlar -->
                <div class="px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Notlar</h3>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">
                            Notlar
                        </label>
                        <textarea name="notes" id="notes" rows="4"
                                  class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                  placeholder="Üye hakkında özel notlar...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.education-members.index') }}"
                       class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors">
                        İptal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Üyeyi Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
