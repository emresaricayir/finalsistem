@extends('admin.layouts.app')

@section('title', 'Aidat Detayı')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-eye mr-2 text-blue-500"></i>
                Aidat Detayı
            </h1>
            <p class="mt-2 text-gray-600">{{ $due->member->full_name }} üyesinin aidat bilgileri.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('admin.dues.index') }}" class="btn-secondary px-6 py-3 rounded-xl font-medium flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Geri Dön
            </a>
        </div>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $due->member->full_name }}</h2>
                    <p class="text-gray-600">{{ $due->member->email }}</p>
                </div>
            </div>
            <div class="text-right">
                @if($due->status === 'paid')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i>
                        Ödendi
                    </span>
                @elseif($due->status === 'overdue')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Gecikmiş
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-2"></i>
                        Beklemede
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Due Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                Aidat Bilgileri
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar text-purple-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Dönem</p>
                            <p class="text-sm text-gray-500">
                                @php
                                    $months = [
                                        1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                                        5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                                        9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                                    ];
                                @endphp
                                {{ $months[$due->month] }} {{ $due->year }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Tutar</p>
                            <p class="text-sm text-gray-500">{{ number_format($due->amount, 2) }} €</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Son Ödeme Tarihi</p>
                            <p class="text-sm text-gray-500">{{ $due->due_date->format('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>

                @if($due->notes)
                    <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center mt-1">
                                <i class="fas fa-sticky-note text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Notlar</p>
                                <p class="text-sm text-gray-500">{{ $due->notes }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Member Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-user mr-2 text-blue-500"></i>
                Üye Bilgileri
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Ad Soyad</p>
                            <p class="text-sm text-gray-500">{{ $due->member->full_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-envelope text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">E-posta</p>
                            <p class="text-sm text-gray-500">{{ $due->member->email }}</p>
                        </div>
                    </div>
                </div>

                @if($due->member->phone)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-phone text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Telefon</p>
                                <p class="text-sm text-gray-500">{{ $due->member->phone }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-orange-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Aylık Aidat</p>
                            <p class="text-sm text-gray-500">{{ number_format($due->member->monthly_dues, 2) }} €</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-toggle-on text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Üyelik Durumu</p>
                            <p class="text-sm text-gray-500">
                                @if($due->member->status === 'active')
                                    <span class="text-green-600">Aktif</span>
                                @elseif($due->member->status === 'inactive')
                                    <span class="text-red-600">Pasif</span>
                                @else
                                    <span class="text-yellow-600">Askıya Alınmış</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Section -->
    @if($due->payments->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">
                <i class="fas fa-credit-card mr-2 text-green-500"></i>
                Ödemeler
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tarih
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tutar
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ödeme Yöntemi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Makbuz No
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($due->payments as $payment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->payment_date->format('d.m.Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                    {{ number_format($payment->amount, 2) }} €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->payment_method }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->receipt_number ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Record Information -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">
            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
            Kayıt Bilgileri
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-plus text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Oluşturulma Tarihi</p>
                        <p class="text-sm text-gray-500">{{ $due->created_at->format('d.m.Y H:i') }}</p>
                        <p class="text-xs text-gray-400">{{ $due->created_at->diffForHumansTr() }}</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-check text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Güncellenme Tarihi</p>
                        <p class="text-sm text-gray-500">{{ $due->updated_at->format('d.m.Y H:i') }}</p>
                        <p class="text-xs text-gray-400">{{ $due->updated_at->diffForHumansTr() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
