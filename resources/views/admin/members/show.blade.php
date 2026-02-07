@extends('admin.layouts.app')

@section('title', 'Üye Detayı')

@section('content')
<style>
    .tab-button.active {
        border-color: #3b82f6;
        color: #2563eb;
    }

    .tab-button:hover {
        color: #374151;
        border-color: #d1d5db;
    }

    .tab-content {
        transition: all 0.3s ease;
    }
</style>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">
            <i class="fas fa-user mr-2"></i>
            Üye Detayı
        </h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.members.edit', $member) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Düzenle
            </a>
            <a href="{{ route('admin.members.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri
            </a>
        </div>
    </div>

    <!-- Member Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Basic Info -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-user mr-2"></i>
                        Kişisel Bilgiler
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ad Soyad</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $member->full_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">E-posta</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if(str_contains($member->email, '@temp.local'))
                                    <span class="text-amber-600 font-medium">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $member->email }}
                                    </span>
                                    <span class="ml-2 px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded-full">
                                        Geçici Email
                                    </span>
                                @else
                                    {{ $member->email }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Telefon</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $member->phone ?: 'Belirtilmemiş' }}</p>
                        </div>

                        <div>
                                                          <label class="block text-sm font-medium text-gray-700">Üye Numarası</label>
                                                          <p class="mt-1 text-sm text-gray-900">{{ $member->member_no }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Doğum Tarihi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $member->birth_date ? $member->birth_date->format('d.m.Y') : 'Belirtilmemiş' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meslek</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $member->occupation ?: 'Belirtilmemiş' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Üyelik Tarihi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $member->membership_date->format('d.m.Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Durum</label>
                            <div class="mt-1">
                                @if($member->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Aktif
                                    </span>
                                @elseif($member->status === 'inactive')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-pause-circle mr-1"></i>
                                        Pasif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-ban mr-1"></i>
                                        Askıya Alınmış
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Aylık Aidat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ number_format($member->monthly_dues, 2) }} €</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Şifre Durumu</label>
                            <div class="mt-1">
                                @if(is_null($member->password))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Şifre Yok
                                    </span>
                                    <p class="mt-2 text-xs text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Şifre oluşturmak için üye düzenleme sayfasını kullanın.
                                    </p>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Şifre Mevcut
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ödeme Yöntemi</label>
                            <div class="mt-1">
                                @if($member->payment_method)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($member->payment_method === 'cash') bg-green-100 text-green-800
                                        @elseif($member->payment_method === 'bank_transfer') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        @if($member->payment_method === 'cash')
                                            <i class="fas fa-money-bill-wave mr-1"></i>Nakit
                                        @elseif($member->payment_method === 'bank_transfer')
                                            <i class="fas fa-university mr-1"></i>Banka Transferi
                                        @else
                                            <i class="fas fa-question mr-1"></i>{{ $member->payment_method }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">Belirtilmemiş</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gizlilik Politikası Rızası</label>
                            <div class="mt-1">
                                @if($member->privacy_consent)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Verildi
                                        @if($member->privacy_consent_date)
                                            <span class="ml-1">({{ $member->privacy_consent_date->format('d.m.Y H:i') }})</span>
                                        @endif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Verilmedi / Geri Çekildi
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($member->address)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Adres</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $member->address }}</p>
                        </div>
                    @endif

                    @if($member->notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Notlar</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $member->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="space-y-6">
            <!-- Financial Summary -->
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Finansal Özet
                    </h3>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Toplam Aidat:</span>
                            <span class="text-sm font-medium text-red-600">{{ number_format($member->total_dues, 2) }} €</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Ödenen Aidat:</span>
                            <span class="text-sm font-medium text-green-600">{{ number_format($member->paid_dues, 2) }} €</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Aylık Aidat:</span>
                            <span class="text-sm font-medium text-blue-600">{{ number_format($member->monthly_dues, 2) }} €</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-bolt mr-2"></i>
                        Hızlı İşlemler
                    </h3>

                    <div class="space-y-3">
                        <a href="{{ route('admin.payments.create') }}?member_id={{ $member->id }}" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <i class="fas fa-credit-card mr-2"></i>
                            Ödeme Al
                        </a>

                        <button onclick="alert('Aidatlar sistem tarafından otomatik oluşturulur. Manuel aidat eklenemez.')" class="w-full bg-gray-400 cursor-not-allowed text-white px-4 py-2 rounded-lg flex items-center justify-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Aidatlar Otomatik
                        </button>

                        @if($member->payments->count() > 0)
                            <button onclick="showBulkReceiptModal()" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center justify-center">
                                <i class="fas fa-receipt mr-2"></i>
                                Toplu Makbuz
                            </button>

                            <!-- Bilgilendirme Notu -->
                            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                                    <div class="text-sm text-blue-700">
                                        <p class="font-medium mb-1">Spendenbescheinigung Oluşturma</p>
                                        <p>Spendenbescheinigung oluşturmak için ödemeler sekmesine tıklayarak aidatları seçin ve toplu makbuz butonuna tıklayın.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aidat ve Ödeme Geçmişi -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-chart-line mr-2"></i>
                Aidat ve Ödeme Geçmişi
            </h3>

            <!-- Tab Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8">
                    <button onclick="switchTab('dues')" id="dues-tab" class="tab-button active py-3 px-4 border-b-3 border-blue-500 font-semibold text-base text-blue-600 bg-blue-50 rounded-t-lg transition-all duration-200 hover:bg-blue-100">
                        <i class="fas fa-money-bill mr-2"></i>
                        Aidatlar
                        <span class="ml-2 bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-sm font-medium">{{ $member->dues->count() }}</span>
                    </button>
                    <button onclick="switchTab('payments')" id="payments-tab" class="tab-button py-3 px-4 border-b-3 border-transparent font-semibold text-base text-gray-500 hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50 rounded-t-lg transition-all duration-200">
                        <i class="fas fa-credit-card mr-2"></i>
                        Ödemeler
                        <span class="ml-2 bg-gray-200 text-gray-700 py-1 px-3 rounded-full text-sm font-medium">{{ $member->payments->count() }}</span>
                    </button>
                </nav>
            </div>

            <!-- Dues Tab Content -->
            <div id="dues-content" class="tab-content">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dönem</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vade Tarihi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahsilat Tarihi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödeme Yöntemi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($member->dues as $due)
                                <tr class="hover:bg-gray-50">
                                    @php
                                        $paymentRecord = null;
                                        if ($due->relationLoaded('paymentDues') && $due->paymentDues->count() > 0) {
                                            $paymentRecord = $due->paymentDues->sortByDesc(fn($payment) => $payment->payment_date?->timestamp ?? 0)->first();
                                        }
                                        if (!$paymentRecord && $due->relationLoaded('payments') && $due->payments->count() > 0) {
                                            $paymentRecord = $due->payments->sortByDesc(fn($payment) => $payment->payment_date?->timestamp ?? 0)->first();
                                        }
                                        if (!$paymentRecord && isset($due->payment)) {
                                            $paymentRecord = $due->payment;
                                        }
                                    @endphp
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $due->month_name }} {{ $due->year }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($due->amount, 2) }} €
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $due->due_date->format('d.m.Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($due->status === 'paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Ödendi
                                            </span>
                                        @elseif($due->status === 'overdue')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Gecikmiş
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Bekliyor
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $paymentRecord && $paymentRecord->payment_date ? $paymentRecord->payment_date->format('d.m.Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($due->status === 'paid' && $paymentRecord)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($paymentRecord->payment_method === 'cash') bg-green-100 text-green-800
                                                @elseif($paymentRecord->payment_method === 'bank_transfer') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @if($paymentRecord->payment_method === 'cash')
                                                    <i class="fas fa-money-bill-wave mr-1"></i>Nakit
                                                @elseif($paymentRecord->payment_method === 'bank_transfer')
                                                    <i class="fas fa-university mr-1"></i>Banka Transferi
                                                @else
                                                    <i class="fas fa-question mr-1"></i>{{ $paymentRecord->payment_method_text }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Aidat kaydı bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payments Tab Content -->
            <div id="payments-content" class="tab-content hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAllPayments" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aidat Dönemi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Yöntem</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Makbuz No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kaydeden</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $sortedPayments = $member->payments->sortBy(function($payment) {
                                    // Determine the earliest due_date tied to this payment
                                    if ($payment->dues && $payment->dues->count() > 0) {
                                        $min = $payment->dues->min('due_date');
                                        return $min ? $min->timestamp : PHP_INT_MAX;
                                    }
                                    if ($payment->due && $payment->due->due_date) {
                                        return $payment->due->due_date->timestamp;
                                    }
                                    return PHP_INT_MAX; // payments without due fall to the end
                                });
                            @endphp
                            @forelse($sortedPayments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox"
                                               name="selected_payments[]"
                                               value="{{ $payment->id }}"
                                               class="payment-checkbox w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                                               data-amount="{{ $payment->amount }}"
                                               data-date="{{ $payment->payment_date->format('d.m.Y') }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @php
                                            // Çoklu due ilişkisi varsa due_date'e göre sırala, yoksa tekil due'u göster
                                            $periods = [];
                                            if ($payment->dues && $payment->dues->count() > 0) {
                                                $sortedDues = $payment->dues->sortBy(function($d) {
                                                    return $d->due_date ? $d->due_date->timestamp : 0;
                                                });
                                                foreach ($sortedDues as $d) {
                                                    $monthText = $d->month_name ?? ($d->due_date ? $d->due_date->format('F') : '');
                                                    $yearText = $d->year ?? ($d->due_date ? $d->due_date->year : '');
                                                    if ($monthText && $yearText) {
                                                        $periods[] = $monthText . ' ' . $yearText;
                                                    }
                                                }
                                            } elseif ($payment->due) {
                                                $monthText = $payment->due->month_name ?? ($payment->due->due_date ? $payment->due->due_date->format('F') : '');
                                                $yearText = $payment->due->year ?? ($payment->due->due_date ? $payment->due->due_date->year : '');
                                                if ($monthText && $yearText) {
                                                    $periods[] = $monthText . ' ' . $yearText;
                                                }
                                            }
                                        @endphp
                                        {{ !empty($periods) ? implode(', ', $periods) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($payment->amount, 2) }} €
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($payment->payment_method === 'cash') bg-green-100 text-green-800
                                            @elseif($payment->payment_method === 'bank_transfer') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($payment->payment_method === 'cash')
                                                <i class="fas fa-money-bill-wave mr-1"></i>Nakit
                                            @elseif($payment->payment_method === 'bank_transfer')
                                                <i class="fas fa-university mr-1"></i>Banka Transferi
                                            @else
                                                <i class="fas fa-question mr-1"></i>{{ $payment->payment_method_text }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment->receipt_no ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment->recordedBy ? $payment->recordedBy->name : 'Silinmiş Kullanıcı' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.payments.show', $payment) }}"
                                               class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-100 transition-colors duration-200"
                                               title="Görüntüle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button"
                                                    onclick="showDeletePaymentModal({{ $payment->id }}, '{{ number_format($payment->amount, 2) }} €', '{{ $payment->payment_date->format('d.m.Y') }}')"
                                                    class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-100 transition-colors duration-200"
                                                    title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                        Ödeme kaydı bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Payment History -->
    <!-- Digital Signature Section -->
    @if($member->signature)
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-signature mr-2"></i>
                Dijital İmza
            </h3>

            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700">İmza Tarihi:</p>
                        <p class="text-sm text-gray-900">{{ $member->signature_date ? $member->signature_date->format('d.m.Y H:i') : 'Belirtilmemiş' }}</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                        İmzalanmış
                    </div>
                </div>

                <div class="border-2 border-gray-300 rounded-lg bg-white p-4 text-center">
                    <img src="{{ $member->signature }}" alt="Dijital İmza" class="max-w-full max-h-32 mx-auto" style="max-height: 120px;">
                </div>

                <div class="mt-3 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Bu dijital imza üye tarafından başvuru sırasında atılmıştır.
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-signature mr-2"></i>
                Dijital İmza
            </h3>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">İmza Bulunamadı</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Bu üye için dijital imza kaydı bulunmamaktadır. Eski üyelikler için normal durumdur.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    @if($member->signature)
    <!-- Digital Signature Section -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                <i class="fas fa-signature mr-2"></i>
                Dijital İmza
            </h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Signature Preview -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">İmza Önizlemesi</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                        <img src="{{ $member->signature }}"
                             alt="Dijital İmza"
                             class="max-w-full h-auto max-h-32 mx-auto border border-gray-200 bg-white p-2 rounded"
                             style="image-rendering: crisp-edges;">
                    </div>
                </div>

                <!-- Signature Info -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">İmza Tarihi</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $member->signature_date ? $member->signature_date->format('d.m.Y H:i:s') : 'Belirtilmemiş' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Veri Boyutu</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ number_format(strlen($member->signature) / 1024, 2) }} KB
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Format</label>
                        <p class="mt-1 text-sm text-gray-900">
                            @if(strpos($member->signature, 'data:image/png') === 0)
                                PNG (Base64)
                            @elseif(strpos($member->signature, 'data:image/jpeg') === 0)
                                JPEG (Base64)
                            @else
                                Base64 Encoded Image
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Durum</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Mevcut
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex space-x-3">
                    <button type="button"
                            onclick="downloadSignature()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                        <i class="fas fa-download mr-2"></i>
                        İmzayı İndir
                    </button>

                    <button type="button"
                            onclick="viewSignatureFullSize()"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                        <i class="fas fa-expand mr-2"></i>
                        Tam Boyut Görüntüle
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
    function downloadSignature() {
        const signatureData = '{{ $member->signature }}';
        const link = document.createElement('a');
        link.href = signatureData;
        link.download = 'imza-{{ $member->member_no }}.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function viewSignatureFullSize() {
        const signatureData = '{{ $member->signature }}';
        const newWindow = window.open('', '_blank');
        newWindow.document.write(`
            <html>
                <head><title>Dijital İmza - {{ $member->full_name }}</title></head>
                <body style="margin: 0; padding: 20px; background: #f5f5f5; text-align: center;">
                    <h2>{{ $member->full_name }} - Dijital İmza</h2>
                    <div style="background: white; padding: 20px; border-radius: 8px; display: inline-block;">
                        <img src="${signatureData}" style="max-width: 100%; height: auto; border: 1px solid #ddd;">
                    </div>
                    <p style="margin-top: 20px; color: #666;">
                        İmza Tarihi: {{ $member->signature_date ? $member->signature_date->format('d.m.Y H:i:s') : 'Belirtilmemiş' }}
                    </p>
                </body>
            </html>
        `);
    }

    // Toplu Makbuz Modal
    function showBulkReceiptModal() {
        const selectedPayments = Array.from(document.querySelectorAll('.payment-checkbox:checked'));

        if (selectedPayments.length === 0) {
            alert('Lütfen en az bir ödeme seçin.');
            return;
        }

        const totalAmount = selectedPayments.reduce((sum, checkbox) => sum + parseFloat(checkbox.dataset.amount), 0);
        const paymentCount = selectedPayments.length;

        // Modal içeriğini güncelle
        document.getElementById('selectedPaymentCount').textContent = paymentCount;
        document.getElementById('selectedTotalAmount').textContent = totalAmount.toFixed(2);

        // Modal'ı göster
        document.getElementById('bulkReceiptModal').classList.remove('hidden');
    }

    function closeBulkReceiptModal() {
        document.getElementById('bulkReceiptModal').classList.add('hidden');
    }

    function generateBulkReceipt() {
        const selectedPayments = Array.from(document.querySelectorAll('.payment-checkbox:checked'));
        const paymentIds = selectedPayments.map(checkbox => checkbox.value);

        if (paymentIds.length === 0) {
            alert('Lütfen en az bir ödeme seçin.');
            return;
        }

        // Toplu makbuz URL'sine yönlendir
        const url = `{{ route('admin.payments.bulk-receipt') }}?payment_ids=${paymentIds.join(',')}&member_id={{ $member->id }}`;
        window.open(url, '_blank');

        closeBulkReceiptModal();
    }

    // Tüm ödemeleri seç/seçme
    document.getElementById('selectAllPayments').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.payment-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Modal dışına tıklayınca kapat
    document.getElementById('bulkReceiptModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBulkReceiptModal();
        }
    });

    // Tab switching functionality
    function switchTab(tabName) {
        console.log('Switching to tab:', tabName); // Debug log

        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-blue-500', 'text-blue-600', 'bg-blue-50');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab content
        const targetContent = document.getElementById(tabName + '-content');
        if (targetContent) {
            targetContent.classList.remove('hidden');
            console.log('Showing content for:', tabName); // Debug log
        } else {
            console.error('Content not found for:', tabName); // Debug log
        }

        // Add active class to selected tab
        const activeTab = document.getElementById(tabName + '-tab');
        if (activeTab) {
            activeTab.classList.add('active', 'border-blue-500', 'text-blue-600', 'bg-blue-50');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            console.log('Activated tab:', tabName); // Debug log
        } else {
            console.error('Tab not found for:', tabName); // Debug log
        }
    }

    // Initialize tabs on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, initializing tabs...'); // Debug log
        
        // Check URL hash first (e.g., #payments)
        const hash = window.location.hash.replace('#', '');
        if (hash && (hash === 'payments' || hash === 'dues')) {
            console.log('Switching to tab from URL hash:', hash);
            switchTab(hash);
            return;
        }
        
        // Check if there's an active tab from session (e.g., after payment deletion)
        @if(session('active_tab'))
            console.log('Switching to tab from session:', '{{ session('active_tab') }}');
            switchTab('{{ session('active_tab') }}');
        @else
            // Ensure dues tab is active by default
            console.log('Switching to default tab: dues');
            switchTab('dues');
        @endif
    });
    </script>

    <!-- Toplu Makbuz Modal -->
    <div id="bulkReceiptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Toplu Makbuz Oluştur</h3>
                    <button onclick="closeBulkReceiptModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-receipt text-purple-600 mr-3 mt-0.5"></i>
                            <div class="text-sm text-purple-800">
                                <p class="font-semibold mb-1">Seçilen Ödemeler:</p>
                                <p>Toplam <span id="selectedPaymentCount">0</span> adet ödeme</p>
                                <p>Toplam tutar: <span id="selectedTotalAmount">0.00</span> €</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600">
                        Seçilen ödemeler için toplu makbuz oluşturulacaktır. Makbuz yeni bir pencerede açılacaktır.
                    </p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeBulkReceiptModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        İptal
                    </button>
                    <button type="button" onclick="generateBulkReceipt()"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                        <i class="fas fa-receipt mr-2"></i>
                        Makbuz Oluştur
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Tab switching functionality
    function switchTab(tabName) {
        console.log('Switching to tab:', tabName); // Debug log

        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // Remove active class from all tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-blue-500', 'text-blue-600', 'bg-blue-50');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab content
        const targetContent = document.getElementById(tabName + '-content');
        if (targetContent) {
            targetContent.classList.remove('hidden');
            console.log('Showing content for:', tabName); // Debug log
        } else {
            console.error('Content not found for:', tabName); // Debug log
        }

        // Add active class to selected tab
        const activeTab = document.getElementById(tabName + '-tab');
        if (activeTab) {
            activeTab.classList.add('active', 'border-blue-500', 'text-blue-600', 'bg-blue-50');
            activeTab.classList.remove('border-transparent', 'text-gray-500');
            console.log('Activated tab:', tabName); // Debug log
        } else {
            console.error('Tab not found for:', tabName); // Debug log
        }
    }

    // Toplu Makbuz Modal
    function showBulkReceiptModal() {
        const selectedPayments = Array.from(document.querySelectorAll('.payment-checkbox:checked'));

        if (selectedPayments.length === 0) {
            alert('Lütfen en az bir ödeme seçin.');
            return;
        }

        const totalAmount = selectedPayments.reduce((sum, checkbox) => sum + parseFloat(checkbox.dataset.amount), 0);
        const paymentCount = selectedPayments.length;

        // Modal içeriğini güncelle
        document.getElementById('selectedPaymentCount').textContent = paymentCount;
        document.getElementById('selectedTotalAmount').textContent = totalAmount.toFixed(2);

        // Modal'ı göster
        document.getElementById('bulkReceiptModal').classList.remove('hidden');
    }

    function closeBulkReceiptModal() {
        document.getElementById('bulkReceiptModal').classList.add('hidden');
    }

    function generateBulkReceipt() {
        const selectedPayments = Array.from(document.querySelectorAll('.payment-checkbox:checked'));
        const paymentIds = selectedPayments.map(checkbox => checkbox.value);

        if (paymentIds.length === 0) {
            alert('Lütfen en az bir ödeme seçin.');
            return;
        }

        // Toplu makbuz URL'sine yönlendir
        const url = `{{ route('admin.payments.bulk-receipt') }}?payment_ids=${paymentIds.join(',')}&member_id={{ $member->id }}`;
        window.open(url, '_blank');

        closeBulkReceiptModal();
    }

    // Tüm ödemeleri seç/seçme
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAllPayments');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.payment-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }

        // Modal dışına tıklayınca kapat
        const modal = document.getElementById('bulkReceiptModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBulkReceiptModal();
                }
            });
        }
    });

    function downloadSignature() {
        const signatureData = '{{ $member->signature }}';
        const link = document.createElement('a');
        link.href = signatureData;
        link.download = '{{ $member->full_name }}_imza.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function viewSignatureFullSize() {
        const signatureData = '{{ $member->signature }}';
        const newWindow = window.open('', '_blank');
        newWindow.document.write(`
            <html>
                <head><title>Dijital İmza - {{ $member->full_name }}</title></head>
                <body style="margin: 0; padding: 20px; background: #f5f5f5; text-align: center;">
                    <h2>{{ $member->full_name }} - Dijital İmza</h2>
                    <div style="background: white; padding: 20px; border-radius: 8px; display: inline-block;">
                        <img src="${signatureData}" style="max-width: 100%; height: auto; border: 1px solid #ddd;">
                    </div>
                    <p style="margin-top: 20px; color: #666;">
                        İmza Tarihi: {{ $member->signature_date ? $member->signature_date->format('d.m.Y H:i:s') : 'Belirtilmemiş' }}
                    </p>
                </body>
            </html>
        `);
    }

    // Ödeme Silme Modal Fonksiyonları
    function showDeletePaymentModal(paymentId, amount, date) {
        document.getElementById('deletePaymentAmount').textContent = amount;
        document.getElementById('deletePaymentDate').textContent = date;
        document.getElementById('deletePaymentForm').action = `/admin/payments/${paymentId}`;
        document.getElementById('deletePaymentModal').classList.remove('hidden');
    }

    function closeDeletePaymentModal() {
        document.getElementById('deletePaymentModal').classList.add('hidden');
    }

    // Modal dışına tıklayınca kapat
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deletePaymentModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeletePaymentModal();
                }
            });
        }
    });
</script>

<!-- Ödeme Silme Modal -->
<div id="deletePaymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Ödeme Silme Onayı</h3>
                <button onclick="closeDeletePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="mb-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-0.5"></i>
                        <div class="text-sm text-red-800">
                            <p class="font-semibold mb-2">Ödemeyi silmek istediğinizden emin misiniz?</p>
                            <div class="space-y-1">
                                <p><strong>Tutar:</strong> <span id="deletePaymentAmount"></span></p>
                                <p><strong>Tarih:</strong> <span id="deletePaymentDate"></span></p>
                            </div>
                            <p class="mt-3 text-xs text-red-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                Bu işlem geri alınamaz. İlgili aidatlar "bekleyen" veya "gecikmiş" durumuna döndürülecektir.
                            </p>
                        </div>
                    </div>
                </div>

                <form id="deletePaymentForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                    <input type="hidden" name="redirect_to" value="member_detail_payments">

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDeletePaymentModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            İptal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>
                            Sil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
