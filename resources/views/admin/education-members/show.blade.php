@extends('admin.layouts.app')

@section('title', 'Eğitim Üyesi Detayları')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Eğitim Üyesi Detayları</h1>
                <p class="text-slate-600 mt-1">{{ $educationMember->full_name }} - {{ $educationMember->student_full_name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.education-members.edit', $educationMember) }}"
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Düzenle
                </a>
                <a href="{{ route('admin.education-members.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Geri Dön
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Üst Kısım - Üye Bilgileri ve Durum -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Üye Bilgileri -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900">Üye Bilgileri</h3>
                    </div>
                    <div class="px-6 py-4 space-y-6">
                        <!-- Temel Bilgiler -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Veli Adı</label>
                                <p class="text-slate-900 font-medium">{{ $educationMember->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Veli Soyadı</label>
                                <p class="text-slate-900 font-medium">{{ $educationMember->surname }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Öğrenci Adı</label>
                                <p class="text-slate-900 font-medium">{{ $educationMember->student_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Öğrenci Soyadı</label>
                                <p class="text-slate-900 font-medium">{{ $educationMember->student_surname }}</p>
                            </div>
                        </div>

                        <!-- İletişim Bilgileri -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">E-posta</label>
                                <p class="text-slate-900">{{ $educationMember->email ?: '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Telefon</label>
                                <p class="text-slate-900">{{ $educationMember->phone ?: '-' }}</p>
                            </div>
                        </div>

                        <!-- Üyelik Bilgileri -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Üyelik Tarihi</label>
                                <p class="text-slate-900">{{ $educationMember->membership_date->format('d.m.Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Aylık Aidat</label>
                                <p class="text-slate-900 font-medium">€{{ number_format($educationMember->monthly_dues, 2) }}</p>
                            </div>
                        </div>

                        <!-- Notlar -->
                        @if($educationMember->notes)
                            <div>
                                <label class="block text-sm font-medium text-slate-500 mb-1">Notlar</label>
                                <p class="text-slate-900">{{ $educationMember->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Durum -->
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-900">Durum</h3>
                    </div>
                    <div class="px-6 py-4">
                        @if($educationMember->status == 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-pause-circle mr-2"></i>
                                Pasif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Alt Kısım - Aidat Geçmişi -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Aidat Geçmişi</h3>
                <p class="text-sm text-slate-500 mt-1">Tüm aidatlar ve ödeme durumları</p>
            </div>
            <div class="px-6 py-4">
                @if($educationMember->dues->count() > 0)
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach($educationMember->dues->sortByDesc('due_date') as $due)
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-sm font-medium text-slate-900">
                                            @php
                                                $monthNames = [
                                                    1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                                                    5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                                                    9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                                                ];
                                            @endphp
                                            {{ $monthNames[$due->due_date->month] }} {{ $due->due_date->year }}
                                        </p>
                                        <p class="text-sm font-bold text-slate-900">
                                            €{{ number_format($due->amount, 2) }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span>Vade: {{ $due->due_date->format('d.m.Y') }}</span>
                                        @if($due->paid_date)
                                            <span>Ödeme: {{ $due->paid_date->format('d.m.Y') }}</span>
                                        @endif
                                    </div>
                                    @if($due->notes)
                                        <p class="text-xs text-slate-600 mt-1 italic">{{ $due->notes }}</p>
                                    @endif
                                </div>
                                <div class="ml-4 flex flex-col items-end space-y-2">
                                    @if($due->status == 'paid')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Ödendi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>
                                            Ödenmemiş
                                        </span>
                                    @endif

                                    @if($due->status != 'paid')
                                        <button onclick="openPaymentModal({{ $due->id }}, {{ $due->amount }})"
                                                class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors">
                                            <i class="fas fa-money-bill-wave mr-1"></i>
                                            Öde
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Özet İstatistikler -->
                    <div class="mt-6 grid grid-cols-2 gap-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-900">{{ $educationMember->dues->where('status', 'paid')->count() }}</p>
                            <p class="text-xs text-green-700">Ödenmiş</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-red-900">{{ $educationMember->dues->where('status', '!=', 'paid')->count() }}</p>
                            <p class="text-xs text-red-700">Ödenmemiş</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500">Henüz aidat oluşturulmamış</p>
                        <p class="text-sm text-slate-400 mt-1">Yıllık aidat oluştur butonunu kullanarak aidatları oluşturabilirsiniz</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Aidat Ödemesi</h3>
            </div>
            <form id="paymentForm" method="POST">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Tutar
                        </label>
                        <input type="text" id="paymentAmount" readonly
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-900">
                    </div>
                    <div>
                        <label for="paid_date" class="block text-sm font-medium text-slate-700 mb-2">
                            Ödeme Tarihi <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="paid_date" id="paid_date" required
                               value="{{ now()->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-slate-700 mb-2">
                            Ödeme Yöntemi <span class="text-red-500">*</span>
                        </label>
                        <select name="payment_method" id="payment_method" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Ödeme yöntemi seçin</option>
                            <option value="cash">Nakit</option>
                            <option value="bank_transfer">Banka Havalesi</option>
                        </select>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">
                            Notlar
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Ödeme ile ilgili notlar..."></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end space-x-3">
                    <button type="button" onclick="closePaymentModal()"
                            class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors">
                        İptal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        Ödemeyi Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openPaymentModal(dueId, amount) {
    document.getElementById('paymentForm').action = `/admin/education-dues/${dueId}/mark-paid`;
    document.getElementById('paymentAmount').value = `€${amount.toFixed(2)}`;
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}
</script>
@endsection
