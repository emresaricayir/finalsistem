@extends('admin.layouts.app')

@section('title', 'Toplu Ödeme')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Toplu Ödeme</h1>
                    <p class="text-slate-600 mt-1">Seçilen velilerin aidatlarını ödendi olarak işaretleyin</p>
                </div>
                <a href="{{ route('admin.education-members.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Velilere Dön
                </a>
            </div>
        </div>

        <!-- Month Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Ay Filtresi</h3>
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <div>
                    <label for="year" class="block text-sm font-medium text-slate-700 mb-2">
                        Yıl
                    </label>
                    <select name="year" id="year"
                            class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @foreach($availableYears as $availableYear)
                            <option value="{{ $availableYear }}" {{ $availableYear == $year ? 'selected' : '' }}>
                                {{ $availableYear }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="month" class="block text-sm font-medium text-slate-700 mb-2">
                        Ay
                    </label>
                    <select name="month" id="month"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @php
                            $monthNames = [
                                1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                                5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                                9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                            ];
                        @endphp
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                {{ $monthNames[$i] }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrele
                    </button>
                </div>
            </form>
            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>{{ $year }} yılı {{ $monthNames[$month] ?? $month }} ayı</strong> için bekleyen aidatları gösteriyor.
                </p>
            </div>
        </div>

        <!-- Bulk Payment Form -->
        <form action="{{ route('admin.education-payments.bulk.process') }}" method="POST" id="bulkPaymentForm">
            @csrf
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="month" value="{{ $month }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Payment Details -->
                <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-fit sticky top-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Ödeme Bilgileri</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-slate-700 mb-2">
                                Ödeme Tarihi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="payment_date" id="payment_date" required
                                   value="{{ now()->format('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">
                                Notlar
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Ödeme ile ilgili notlar..."></textarea>
                        </div>
                        <div class="mt-6">
                            <button type="submit" id="processBulkPaymentBtn"
                                    class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-lg font-medium rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    disabled>
                                <i class="fas fa-check-circle mr-2"></i>
                                Seçilenleri Ödendi İşaretle (<span id="selectedMembersCount">0</span>)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Member List -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-slate-900">
                                {{ $year }} yılı {{ $monthNames[$month] ?? $month }} ayı - Bekleyen Aidatlar
                            </h3>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="selectAllMembers()"
                                        class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition-colors">
                                    Tümünü Seç
                                </button>
                                <button type="button" onclick="deselectAllMembers()"
                                        class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition-colors">
                                    Hiçbirini Seçme
                                </button>
                            </div>
                        </div>

                        @if($members->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($members as $member)
                                    @if($member->dues->count() > 0)
                                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 flex items-center space-x-3">
                                            <input type="checkbox" name="selected_members[]" value="{{ $member->id }}"
                                                   class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 member-checkbox"
                                                   data-due-amount="{{ $member->dues->first()->amount ?? 0 }}">
                                            <div>
                                                <p class="text-sm font-medium text-slate-900">{{ $member->name }} {{ $member->surname }} (Veli)</p>
                                                <p class="text-xs text-slate-600">{{ $member->student_name }} {{ $member->student_surname }} (Öğrenci)</p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        Aidat: €{{ number_format($member->dues->first()->amount ?? 0, 2) }}
                                    </p>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-check-circle text-green-500 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-slate-900 mb-2">Bu Ay İçin Ödeme Bekleyen Üye Yok</h3>
                                <p class="text-slate-600 mb-4">
                                    {{ $year }} yılı {{ $monthNames[$month] ?? $month }} ayı için:
                                </p>
                                <ul class="text-sm text-slate-500 space-y-1">
                                    <li>• Tüm aidatlar ödenmiş olabilir</li>
                                    <li>• Bu ay için aidat oluşturulmamış olabilir</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function selectAllMembers() {
    document.querySelectorAll('.member-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelectedCount();
}

function deselectAllMembers() {
    document.querySelectorAll('.member-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedCount();
}

function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('.member-checkbox:checked').length;
    document.getElementById('selectedMembersCount').textContent = selectedCount;

    // Enable/disable submit button
    const submitBtn = document.getElementById('processBulkPaymentBtn');
    if (selectedCount > 0) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// Update count when checkboxes change
document.querySelectorAll('.member-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

// Form validation
document.getElementById('bulkPaymentForm').addEventListener('submit', function(e) {
    const selectedMembers = document.querySelectorAll('.member-checkbox:checked');
    const paymentDate = document.getElementById('payment_date').value;

    if (selectedMembers.length === 0) {
        e.preventDefault();
        alert('En az bir üye seçmelisiniz!');
        return false;
    }

    if (!paymentDate) {
        e.preventDefault();
        alert('Ödeme tarihi girmelisiniz!');
        return false;
    }

    // Confirm before submitting
    if (!confirm(`${selectedMembers.length} üye için aidat ödendi olarak işaretlenecek. Devam etmek istiyor musunuz?`)) {
        e.preventDefault();
        return false;
    }
});

// Initialize count
updateSelectedCount();
</script>
@endsection
