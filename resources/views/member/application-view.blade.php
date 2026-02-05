@extends('layouts.member')

@section('title', 'Başvuru Formu')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-teal-800 mb-2">
            Üyelik Başvuru Formu
        </h1>
        <p class="text-base text-gray-600">
            Doldurduğunuz üyelik başvuru formu
        </p>
    </div>

    <!-- Application Form Display -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
        <!-- Personal Information Section -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-user text-teal-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Kişisel Bilgiler</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-teal-600"></i>Adı / Vorname
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        {{ $member->name }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-teal-600"></i>Soyadı / Nachname
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        {{ $member->surname }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-teal-600"></i>E-Mail
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        {{ $member->email }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2 text-teal-600"></i>Telefon / Telefon
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        {{ $member->phone ?: 'Belirtilmemiş' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-2 text-teal-600"></i>Doğum Tarihi / Geburtsdatum
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        {{ $member->birth_date ? $member->birth_date->format('d.m.Y') : 'Belirtilmemiş' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-teal-600"></i>Doğum Yeri / Geburtsort
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        {{ $member->birth_place ?: 'Belirtilmemiş' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-flag mr-2 text-teal-600"></i>Uyruk / Staatsangehörigkeit
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        {{ $member->nationality ?: 'Belirtilmemiş' }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-briefcase mr-2 text-teal-600"></i>Meslek / Beruf
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        {{ $member->occupation ?: 'Belirtilmemiş' }}
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-home mr-2 text-teal-600"></i>Adres / Anschrift
                </label>
                <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                    {{ $member->address }}
                </div>
            </div>
        </div>

        <!-- Membership Information Section -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-id-card text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Üyelik Bilgileri</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-hashtag mr-2 text-blue-600"></i>Üye No / Mitgliedsnummer
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 font-mono">
                        {{ $member->member_no }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check mr-2 text-blue-600"></i>Üyelik Tarihi / Mitgliedschaftsdatum
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        {{ $member->membership_date->format('d.m.Y') }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-euro-sign mr-2 text-blue-600"></i>Aylık Aidat / Monatlicher Beitrag
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900 font-bold">
                        {{ number_format($member->monthly_dues, 2) }} €
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-credit-card mr-2 text-blue-600"></i>Ödeme Yöntemi / Zahlungsmethode
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        @switch($member->payment_method)
                            @case('cash')
                                Nakit / Bar
                                @break
                            @case('bank_transfer')
                                Banka Havalesi / Banküberweisung
                                @break
                            @case('lastschrift_monthly')
                                Lastschrift (Aylık) / Lastschrift (Monatlich)
                                @break
                            @case('lastschrift_semi_annual')
                                Lastschrift (6 Aylık) / Lastschrift (Halbjährlich)
                                @break
                            @case('lastschrift_annual')
                                Lastschrift (Yıllık) / Lastschrift (Jährlich)
                                @break
                            @default
                                {{ $member->payment_method }}
                        @endswitch
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Status Section -->
        <div class="mb-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Başvuru Durumu</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-info-circle mr-2 text-green-600"></i>Başvuru Durumu / Antragsstatus
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg">
                        @switch($member->application_status)
                            @case('pending')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Onay Bekliyor / Ausstehend
                                </span>
                                @break
                            @case('approved')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Onaylandı / Genehmigt
                                </span>
                                @break
                            @case('rejected')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    Reddedildi / Abgelehnt
                                </span>
                                @break
                            @default
                                {{ $member->application_status }}
                        @endswitch
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-plus mr-2 text-green-600"></i>Başvuru Tarihi / Antragsdatum
                    </label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-gray-900">
                        {{ $member->application_date ? $member->application_date->format('d.m.Y H:i') : 'Belirtilmemiş' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t border-gray-200">
            <a href="{{ route('member.application.pdf') }}"
               class="inline-flex items-center justify-center px-8 py-4 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl">
                <i class="fas fa-download mr-3"></i>
                PDF İndir
            </a>

            <a href="{{ route('member.dashboard') }}"
               class="inline-flex items-center justify-center px-8 py-4 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-700 transition-colors shadow-lg hover:shadow-xl">
                <i class="fas fa-arrow-left mr-3"></i>
                Ana Sayfaya Dön
            </a>
        </div>
    </div>
</div>
@endsection

