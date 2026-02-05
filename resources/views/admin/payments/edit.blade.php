@extends('admin.layouts.app')

@section('title', 'Ödeme Düzenle')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">
            <i class="fas fa-edit mr-2"></i>
            Ödeme Düzenle
        </h1>
        <a href="{{ route('admin.payments.index') }}" class="btn-secondary px-4 py-2 rounded-xl font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Geri
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Ödeme Bilgileri</h2>
        </div>

        <form action="{{ route('admin.payments.update', $payment) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Member Selection -->
                <div>
                    <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Üye *
                    </label>
                    <select name="member_id" id="member_id" required
                            class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Üye Seçin</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ $payment->member_id == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }} ({{ $member->member_no }})
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Selection -->
                <div>
                    <label for="due_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                        Aidat (Opsiyonel)
                    </label>
                    <select name="due_id" id="due_id"
                            class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Aidat Seçin (Opsiyonel)</option>
                        @foreach($dues as $due)
                            <option value="{{ $due->id }}" {{ $payment->due_id == $due->id ? 'selected' : '' }}>
                                {{ $due->due_date->formatTr('F Y') }} - {{ number_format($due->amount, 2) }} €
                            </option>
                        @endforeach
                    </select>
                    @error('due_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-euro-sign mr-2 text-green-500"></i>
                        Tutar *
                    </label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0.01" required
                           value="{{ old('amount', $payment->amount) }}"
                           class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="0.00">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-credit-card mr-2 text-indigo-500"></i>
                        Ödeme Yöntemi *
                    </label>
                    <select name="payment_method" id="payment_method" required
                            class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Ödeme Yöntemi Seçin</option>
                        <option value="cash" {{ $payment->payment_method == 'cash' ? 'selected' : '' }}>Nakit</option>
                        <option value="bank_transfer" {{ $payment->payment_method == 'bank_transfer' ? 'selected' : '' }}>Banka Transferi</option>
                        <option value="lastschrift_monthly" {{ $payment->payment_method == 'lastschrift_monthly' ? 'selected' : '' }}>Lastschrift (Aylık)</option>
                        <option value="lastschrift_semi_annual" {{ $payment->payment_method == 'lastschrift_semi_annual' ? 'selected' : '' }}>Lastschrift (6 Aylık)</option>
                        <option value="lastschrift_annual" {{ $payment->payment_method == 'lastschrift_annual' ? 'selected' : '' }}>Lastschrift (Yıllık)</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Receipt Number -->
                <div>
                    <label for="receipt_no" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-receipt mr-2 text-orange-500"></i>
                        Makbuz Numarası
                    </label>
                    <input type="text" name="receipt_no" id="receipt_no"
                           value="{{ old('receipt_no', $payment->receipt_no) }}"
                           class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Makbuz numarası">
                    @error('receipt_no')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Date -->
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2 text-red-500"></i>
                        Ödeme Tarihi *
                    </label>
                    <input type="date" name="payment_date" id="payment_date" required
                           value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                           class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('payment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                    Açıklama
                </label>
                <textarea name="description" id="description" rows="3"
                          class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Ödeme ile ilgili açıklama...">{{ old('description', $payment->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.payments.index') }}" class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Member selection change handler
document.getElementById('member_id').addEventListener('change', function() {
    const memberId = this.value;
    const dueSelect = document.getElementById('due_id');

    if (memberId) {
        // Clear current options
        dueSelect.innerHTML = '<option value="">Aidat Seçin (Opsiyonel)</option>';

        // Fetch unpaid dues for selected member
        fetch(`/admin/payments/unpaid-dues/${memberId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(dues => {
            dues.forEach(due => {
                const option = document.createElement('option');
                option.value = due.id;
                option.textContent = `${due.due_date} - ${due.amount} €`;
                dueSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching dues:', error);
        });
    }
});
</script>
@endsection
