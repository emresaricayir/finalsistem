@extends('admin.layouts.app')

@section('title', 'Ödeme Detayı')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">
            <i class="fas fa-credit-card mr-2"></i>
            Ödeme Detayı
        </h1>
        <div class="flex space-x-3">


            <a href="{{ route('admin.payments.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri
            </a>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Ödeme Bilgileri</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Member Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Üye Bilgileri
                    </h3>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Ad Soyad:</span>
                            <span class="text-sm text-gray-900">{{ $payment->member->full_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">E-posta:</span>
                            <span class="text-sm text-gray-900">{{ $payment->member->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Telefon:</span>
                            <span class="text-sm text-gray-900">{{ $payment->member->phone ?: 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Üye No:</span>
                            <span class="text-sm text-gray-900">{{ $payment->member->member_no }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                        <i class="fas fa-credit-card mr-2 text-green-500"></i>
                        Ödeme Bilgileri
                    </h3>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Tutar:</span>
                            <span class="text-lg font-bold text-green-600">{{ number_format($payment->amount, 2) }} €</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Ödeme Yöntemi:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $payment->payment_method_text }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Ödeme Tarihi:</span>
                            <span class="text-sm text-gray-900">{{ $payment->payment_date->format('d.m.Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Makbuz No:</span>
                            <span class="text-sm font-mono text-gray-900">{{ $payment->receipt_no ?: 'Belirtilmemiş' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Kaydeden:</span>
                            <span class="text-sm text-gray-900">{{ $payment->recordedBy ? $payment->recordedBy->name : 'Silinmiş Kullanıcı' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Kayıt Tarihi:</span>
                            <span class="text-sm text-gray-900">{{ $payment->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            @if($payment->description)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-3">
                    <i class="fas fa-sticky-note mr-2 text-yellow-500"></i>
                    Açıklama
                </h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-700">{{ $payment->description }}</p>
                </div>
            </div>
            @endif

            <!-- Related Dues (New System) -->
            @if($payment->dues && $payment->dues->count() > 0)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-3">
                    <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                    Ödenen Aidatlar ({{ $payment->dues->count() }} adet)
                </h3>
                <div class="space-y-3">
                    @foreach($payment->dues as $due)
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4 border border-green-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Aidat Dönemi:</span>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::create($due->year, $due->month, 1)->locale('tr')->format('F Y') }}
                                </p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Aidat Tutarı:</span>
                                <p class="text-sm font-semibold text-green-600">{{ number_format($due->amount, 2) }} €</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Vade Tarihi:</span>
                                <p class="text-sm text-gray-900">{{ $due->due_date->format('d.m.Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Durum:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Ödendi
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif($payment->due)
            <!-- Related Due (Old System) -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-3">
                    <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>
                    İlgili Aidat
                </h3>
                <div class="bg-purple-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Aidat Dönemi:</span>
                            <p class="text-sm text-gray-900">{{ $payment->due->due_date->locale('tr')->format('F Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Aidat Tutarı:</span>
                            <p class="text-sm text-gray-900">{{ number_format($payment->due->amount, 2) }} €</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Durum:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Ödendi
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
