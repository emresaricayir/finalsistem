@extends('admin.layouts.app')

@section('title', 'Üyeler')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-users mr-2 text-blue-500"></i>
                Üyeler
            </h1>
            <p class="mt-2 text-gray-600">Sistemdeki tüm üyeleri yönetin ve takip edin.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
            <button onclick="generateEnvelopes()" class="btn-secondary px-6 py-3 rounded-xl font-medium flex items-center shadow-lg hover:shadow-xl transition-all duration-200">
                <i class="fas fa-envelope mr-2"></i>
                Zarf Yazdır (220x110)
            </button>
            <a href="{{ route('admin.donation-certificates.index') }}" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-xl flex items-center justify-center font-medium shadow-lg hover:shadow-xl transition-all duration-200 group">
                <div class="bg-white/20 p-1 rounded-lg mr-3 group-hover:bg-white/30 transition-all duration-200">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <div class="font-semibold">Spendenbescheinigung</div>
                </div>
            </a>
            <a href="{{ route('admin.members.import.form') }}" class="btn-success text-white px-6 py-3 rounded-xl font-medium flex items-center shadow-lg hover:shadow-xl transition-all duration-200">
                <i class="fas fa-file-excel mr-2"></i>
                Excel'den İçe Aktar
            </a>
            <a href="{{ route('admin.members.deleted') }}" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl font-medium flex items-center shadow-lg hover:shadow-xl transition-all duration-200 mr-3">
                <i class="fas fa-trash-alt mr-2"></i>
                Silinen Üyeler
            </a>
            <a href="{{ route('admin.members.create') }}" class="btn-primary text-white px-6 py-3 rounded-xl font-medium flex items-center shadow-lg hover:shadow-xl transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>
                Yeni Üye Ekle
            </a>
        </div>
    </div>

    <!-- Privacy Consent Withdrawals Section -->
    @if(isset($recentPrivacyWithdrawals) && $recentPrivacyWithdrawals->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-b border-yellow-200 px-6 py-4">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Gizlilik Politikası Rıza Geri Çekmeleri</h2>
                    <p class="text-gray-600 text-sm">DSGVO gereği üyeler tarafından rıza geri çekildi</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-4">
                @foreach($recentPrivacyWithdrawals as $withdrawal)
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-user-shield text-yellow-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $withdrawal->member->full_name }}</h3>
                                    <p class="text-sm text-gray-600">Üye No: {{ $withdrawal->member->member_no }} | Email: {{ $withdrawal->member->email }}</p>
                                </div>
                            </div>
                            @if($withdrawal->notes)
                            <div class="mt-3 bg-white rounded-lg p-3 border border-yellow-200">
                                <p class="text-sm text-gray-700"><strong>Not:</strong> {{ $withdrawal->notes }}</p>
                            </div>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-clock mr-1"></i>
                                Geri Çekilme Tarihi: {{ $withdrawal->withdrawn_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('admin.members.show', $withdrawal->member->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg">
                                <i class="fas fa-eye mr-2"></i>
                                Üye Detayı
                            </a>
                            <form action="{{ route('admin.members.privacy-withdrawals.mark-notified', $withdrawal->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" 
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                                    <i class="fas fa-check mr-2"></i>
                                    Okundu Olarak İşaretle
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Deletion Requests Section -->
    @if(isset($pendingDeletionRequests) && $pendingDeletionRequests->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-red-200 overflow-hidden">
        <div class="bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200 px-6 py-4">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-lg mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Bekleyen Silme Talepleri</h2>
                    <p class="text-gray-600 text-sm">DSGVO gereği üye silme talepleri</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-4">
                @foreach($pendingDeletionRequests as $request)
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="bg-red-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-user-times text-red-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $request->member->full_name }}</h3>
                                    <p class="text-sm text-gray-600">Üye No: {{ $request->member->member_no }} | Email: {{ $request->member->email }}</p>
                                </div>
                            </div>
                            @if($request->reason)
                            <div class="mt-3 bg-white rounded-lg p-3 border border-red-200">
                                <p class="text-sm text-gray-700"><strong>Sebep:</strong> {{ $request->reason }}</p>
                            </div>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-clock mr-1"></i>
                                Talep Tarihi: {{ $request->created_at->format('d.m.Y H:i') }}
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <form action="{{ route('admin.members.deletion-requests.approve', $request->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Bu üyeyi silmek istediğinizden emin misiniz? Üye silinen üyeler listesine taşınacaktır.')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                                    <i class="fas fa-check mr-2"></i>
                                    Onayla ve Sil
                                </button>
                            </form>
                            <form action="{{ route('admin.members.deletion-requests.reject', $request->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Bu silme talebini reddetmek istediğinizden emin misiniz?')"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                                    <i class="fas fa-times mr-2"></i>
                                    Reddet
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Members Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Modern Search & Filter Section -->
        <div class="p-6 border-b border-gray-100 bg-gradient-to-br from-gray-50 to-white">
            <form id="memberSearchForm" method="GET" action="{{ route('admin.members.index') }}">
                <!-- Search Bar -->
                <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input
                                id="memberSearchInput"
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Üye ara... (ad, soyad, email, telefon, üye no, adres...)"
                                class="block w-full pl-12 pr-20 py-3.5 text-sm border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center space-x-1">
                                <button
                                    type="button"
                                    id="clearSearchBtn"
                                    class="hidden text-gray-400 hover:text-gray-600 transition-colors p-1"
                                    aria-label="Temizle"
                                >
                                    <i class="fas fa-times-circle text-lg"></i>
                                </button>
                                <span id="searchLoading" class="hidden">
                                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Toggle Button -->
                    <button
                        type="button"
                        onclick="toggleFilters()"
                        class="lg:w-auto px-6 py-3.5 bg-white border-2 border-gray-300 rounded-xl font-medium text-gray-700 hover:bg-gray-50 hover:border-blue-400 transition-all duration-200 shadow-sm flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-filter"></i>
                        <span>Filtreler</span>
                        @if(request('status') || request('email_type') || request('payment_method') || request('phone_presence') || request('address_presence') || request('gender'))
                            <span class="ml-1 px-2 py-0.5 bg-blue-500 text-white text-xs rounded-full">
                                {{ collect([request('status'), request('email_type'), request('payment_method'), request('phone_presence'), request('address_presence'), request('gender')])->filter()->count() }}
                            </span>
                        @endif
                        <i id="filterChevron" class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </button>

                    <!-- Clear All Button -->
                    @if(request('search') || request('status') || request('email_type') || request('payment_method') || request('phone_presence') || request('address_presence') || request('gender'))
                        <a
                            href="{{ route('admin.members.index') }}"
                            class="lg:w-auto px-6 py-3.5 bg-red-50 border-2 border-red-200 rounded-xl font-medium text-red-600 hover:bg-red-100 hover:border-red-300 transition-all duration-200 shadow-sm flex items-center justify-center gap-2"
                        >
                            <i class="fas fa-times"></i>
                            <span>Temizle</span>
                        </a>
                    @endif
                </div>

                <!-- Collapsible Filters -->
                <div id="filtersPanel" class="hidden mt-4 pt-4 border-t border-gray-200">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <!-- Status Filter -->
                        <div class="relative">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-user-check text-blue-500 mr-1"></i>
                                Üye Durumu
                            </label>
                            <select
                                name="status"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm appearance-none"
                                onchange="this.form.submit()"
                            >
                                <option value="">Tümü</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 bottom-3 text-gray-400 pointer-events-none text-xs"></i>
                        </div>

                        <!-- Email Type Filter -->
                        <div class="relative">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-envelope text-green-500 mr-1"></i>
                                Email Türü
                            </label>
                            <select
                                name="email_type"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm appearance-none"
                                onchange="this.form.submit()"
                            >
                                <option value="">Tümü</option>
                                <option value="temporary" {{ request('email_type') == 'temporary' ? 'selected' : '' }}>Geçici Email</option>
                                <option value="regular" {{ request('email_type') == 'regular' ? 'selected' : '' }}>Normal Email</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 bottom-3 text-gray-400 pointer-events-none text-xs"></i>
                        </div>

                        <!-- Payment Method Filter -->
                        <div class="relative">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-credit-card text-purple-500 mr-1"></i>
                                Ödeme Yöntemi
                            </label>
                            <select
                                name="payment_method"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm appearance-none"
                                onchange="this.form.submit()"
                            >
                                <option value="">Tümü</option>
                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Nakit</option>
                                <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Banka Transferi</option>
                                <option value="lastschrift_monthly" {{ request('payment_method') == 'lastschrift_monthly' ? 'selected' : '' }}>Lastschrift (Aylık)</option>
                                <option value="lastschrift_semi_annual" {{ request('payment_method') == 'lastschrift_semi_annual' ? 'selected' : '' }}>Lastschrift (6 Aylık)</option>
                                <option value="lastschrift_annual" {{ request('payment_method') == 'lastschrift_annual' ? 'selected' : '' }}>Lastschrift (Yıllık)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 bottom-3 text-gray-400 pointer-events-none text-xs"></i>
                        </div>

                        <!-- Phone Presence Filter -->
                        <div class="relative">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-phone text-teal-500 mr-1"></i>
                                Telefon Bilgisi
                            </label>
                            <select
                                name="phone_presence"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm appearance-none"
                                onchange="this.form.submit()"
                            >
                                <option value="">Tümü</option>
                                <option value="has" {{ request('phone_presence') == 'has' ? 'selected' : '' }}>Telefonu Olan</option>
                                <option value="missing" {{ request('phone_presence') == 'missing' ? 'selected' : '' }}>Telefonu Olmayan</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 bottom-3 text-gray-400 pointer-events-none text-xs"></i>
                        </div>

                        <!-- Address Presence Filter -->
                        <div class="relative">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-map-marker-alt text-rose-500 mr-1"></i>
                                Adres Bilgisi
                            </label>
                            <select
                                name="address_presence"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm appearance-none"
                                onchange="this.form.submit()"
                            >
                                <option value="">Tümü</option>
                                <option value="has" {{ request('address_presence') == 'has' ? 'selected' : '' }}>Adresi Olan</option>
                                <option value="missing" {{ request('address_presence') == 'missing' ? 'selected' : '' }}>Adresi Olmayan</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 bottom-3 text-gray-400 pointer-events-none text-xs"></i>
                        </div>

                        <!-- Gender Filter -->
                        <div class="relative">
                            <label class="block text-xs font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-venus-mars text-pink-500 mr-1"></i>
                                Cinsiyet
                            </label>
                            <select
                                name="gender"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-sm appearance-none"
                                onchange="this.form.submit()"
                            >
                                <option value="">Tümü</option>
                                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Erkek</option>
                                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Kadın</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 bottom-3 text-gray-400 pointer-events-none text-xs"></i>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Active Filters Display -->
            @if(request('search') || request('status') || request('email_type') || request('payment_method') || request('phone_presence') || request('address_presence') || request('gender'))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-medium text-gray-600">Aktif Filtreler:</span>

                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-search mr-1.5"></i>
                                "{{ Str::limit(request('search'), 30) }}"
                                <a href="{{ route('admin.members.index', array_filter(request()->except('search'))) }}" class="ml-2 hover:text-blue-900">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif

                        @if(request('status'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-user-check mr-1.5"></i>
                                {{ request('status') == 'active' ? 'Aktif' : (request('status') == 'inactive' ? 'Pasif' : 'Askıya Alınmış') }}
                                <a href="{{ route('admin.members.index', array_filter(request()->except('status'))) }}" class="ml-2 hover:text-green-900">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif

                        @if(request('email_type'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-envelope mr-1.5"></i>
                                {{ request('email_type') == 'temporary' ? 'Geçici Email' : 'Normal Email' }}
                                <a href="{{ route('admin.members.index', array_filter(request()->except('email_type'))) }}" class="ml-2 hover:text-purple-900">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif

                        @if(request('payment_method'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-credit-card mr-1.5"></i>
                                @if(request('payment_method') == 'cash') Nakit
                                @elseif(request('payment_method') == 'bank_transfer') Banka Transferi
                                @elseif(request('payment_method') == 'lastschrift_monthly') Lastschrift (Aylık)
                                @elseif(request('payment_method') == 'lastschrift_semi_annual') Lastschrift (6 Aylık)
                                @elseif(request('payment_method') == 'lastschrift_annual') Lastschrift (Yıllık)
                                @endif
                                <a href="{{ route('admin.members.index', array_filter(request()->except('payment_method'))) }}" class="ml-2 hover:text-yellow-900">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif

                        @if(request('phone_presence'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                <i class="fas fa-phone mr-1.5"></i>
                                {{ request('phone_presence') == 'has' ? 'Telefonu Olan' : 'Telefonu Olmayan' }}
                                <a href="{{ route('admin.members.index', array_filter(request()->except('phone_presence'))) }}" class="ml-2 hover:text-teal-900">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif

                        @if(request('address_presence'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                <i class="fas fa-map-marker-alt mr-1.5"></i>
                                {{ request('address_presence') == 'has' ? 'Adresi Olan' : 'Adresi Olmayan' }}
                                <a href="{{ route('admin.members.index', array_filter(request()->except('address_presence'))) }}" class="ml-2 hover:text-rose-900">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif

                        @if(request('gender'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                <i class="fas fa-venus-mars mr-1.5"></i>
                                {{ request('gender') == 'male' ? 'Erkek' : 'Kadın' }}
                                <a href="{{ route('admin.members.index', array_filter(request()->except('gender'))) }}" class="ml-2 hover:text-pink-900">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                        @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.members.generate-filtered-pdf', request()->all()) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm">
                                <i class="fas fa-file-pdf mr-2"></i>
                                PDF olarak yazdır
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <script>
            // Search functionality
            (function(){
                const input = document.getElementById('memberSearchInput');
                const form = document.getElementById('memberSearchForm');
                const loading = document.getElementById('searchLoading');
                const clearBtn = document.getElementById('clearSearchBtn');
                let t;

                function toggleClear(){
                    clearBtn.classList[input.value ? 'remove' : 'add']('hidden');
                }

                function submitWithDebounce(){
                    clearTimeout(t);
                    loading.classList.remove('hidden');
                    toggleClear();
                    if(input.value.length >= 2 || input.value.length === 0) {
                        t = setTimeout(()=>{ form.requestSubmit(); }, 600);
                    } else {
                        loading.classList.add('hidden');
                    }
                }

                input.addEventListener('input', submitWithDebounce);
                form.addEventListener('submit', function(){
                    setTimeout(()=>loading.classList.add('hidden'), 600);
                });
                clearBtn.addEventListener('click', function(){
                    input.value='';
                    toggleClear();
                    form.requestSubmit();
                });
                toggleClear();
            })();

            // Toggle filters panel
            function toggleFilters() {
                const panel = document.getElementById('filtersPanel');
                const chevron = document.getElementById('filterChevron');

                if (panel.classList.contains('hidden')) {
                    panel.classList.remove('hidden');
                    chevron.classList.add('rotate-180');
                } else {
                    panel.classList.add('hidden');
                    chevron.classList.remove('rotate-180');
                }
            }

            // Auto-open filters if any filter is active
            @if(request('status') || request('email_type') || request('payment_method') || request('phone_presence') || request('address_presence'))
                document.addEventListener('DOMContentLoaded', function() {
                    toggleFilters();
                });
            @endif
        </script>

        @if(false) {{-- Old search results message removed - now using badges above --}}
            <div class="px-6 py-3 bg-blue-50 border-b border-blue-100">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        @if(request('search'))
                            "<strong>{{ request('search') }}</strong>" için arama sonuçları
                        @endif
                        @if(request('status'))
                            @if(request('search')) - @endif
                            <strong>{{ request('status') == 'active' ? 'Aktif' : (request('status') == 'inactive' ? 'Pasif' : 'Askıya Alınmış') }}</strong> üyeler gösteriliyor
                        @endif
                        @if(request('email_type'))
                            @if(request('search') || request('status')) - @endif
                            <strong>{{ request('email_type') == 'temporary' ? 'Geçici Email' : 'Normal Email' }}</strong> adresleri gösteriliyor
                        @endif
                        @if(request('payment_method'))
                            @if(request('search') || request('status') || request('email_type')) - @endif
                            <strong>{{ request('payment_method') == 'cash' ? 'Nakit' : 'Banka Transferi' }}</strong> ödeme yöntemi gösteriliyor
                        @endif
                        @if(request('phone_presence'))
                            @if(request('search') || request('status') || request('email_type') || request('payment_method')) - @endif
                            <strong>{{ request('phone_presence') == 'has' ? 'Telefonu Olan' : 'Telefonu Olmayan' }}</strong> üyeler gösteriliyor
                        @endif
                        @if(request('address_presence'))
                            @if(request('search') || request('status') || request('email_type') || request('payment_method') || request('phone_presence')) - @endif
                            <strong>
                                @if(request('address_presence') == 'has')
                                    Adresi Olan
                                @else
                                    Adresi Olmayan
                                @endif
                            </strong> üyeler gösteriliyor
                        @endif
                        ({{ $members->total() }} sonuç)
                    </div>
                </div>
            </div>
        @endif

        <!-- Live summary stats -->
        <div class="px-6 pt-3 pb-1">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2">
                <div class="rounded-xl p-3 bg-gradient-to-br from-gray-50 to-white border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-[11px] uppercase tracking-wide text-gray-500">Toplam Üye</div>
                            <div class="text-lg sm:text-xl font-bold text-gray-900">{{ number_format($totalMembers) }}</div>
                        </div>
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl p-3 bg-gradient-to-br from-blue-50 to-white border border-blue-100/70 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-[11px] uppercase tracking-wide text-blue-600">Filtrelenen</div>
                            <div class="text-lg sm:text-xl font-bold text-blue-700">{{ number_format($members->total()) }}</div>
                        </div>
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                            <i class="fas fa-filter"></i>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl p-3 bg-gradient-to-br from-green-50 to-white border border-green-100/70 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-[11px] uppercase tracking-wide text-green-600">Aktif</div>
                            <div class="text-lg sm:text-xl font-bold text-green-700">{{ number_format($activeMembers) }}</div>
                        </div>
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl p-3 bg-gradient-to-br from-amber-50 to-white border border-amber-100/70 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-[11px] uppercase tracking-wide text-amber-600">Askıda</div>
                            <div class="text-lg sm:text-xl font-bold text-amber-700">{{ number_format($suspendedMembers) }}</div>
                        </div>
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl p-3 bg-gradient-to-br from-rose-50 to-white border border-rose-100/70 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-[11px] uppercase tracking-wide text-rose-600">Pasif</div>
                            <div class="text-lg sm:text-xl font-bold text-rose-700">{{ number_format($inactiveMembers) }}</div>
                        </div>
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-rose-100 flex items-center justify-center text-rose-600">
                            <i class="fas fa-user-slash"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex flex-col items-center">
                                <input type="checkbox" id="selectAll" class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                                <span class="mt-1 text-xs">Tümü</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'surname', 'direction' => request('sort') == 'surname' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700 transition-colors">
                                <i class="fas fa-user mr-2"></i>
                                Üye
                                @if(request('sort') == 'surname')
                                    <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1 text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-phone mr-2"></i>
                            İletişim
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'membership_date', 'direction' => request('sort') == 'membership_date' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700 transition-colors">
                                <i class="fas fa-calendar mr-2"></i>
                                Üyelik Tarihi
                                @if(request('sort') == 'membership_date')
                                    <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1 text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'monthly_dues', 'direction' => request('sort') == 'monthly_dues' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700 transition-colors">
                                <i class="fas fa-money-bill mr-2"></i>
                                Aylık Aidat
                                @if(request('sort') == 'monthly_dues')
                                    <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1 text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-info-circle mr-2"></i>
                            Durum
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'payment_status', 'direction' => request('sort') == 'payment_status' && request('direction') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center hover:text-gray-700 transition-colors">
                                <i class="fas fa-chart-line mr-2"></i>
                                Aidat Durumu
                                @if(request('sort') == 'payment_status')
                                    <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }} ml-1 text-blue-500"></i>
                                @else
                                    <i class="fas fa-sort ml-1 text-gray-400"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Gizlilik Rızası
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected_members[]" value="{{ $member->id }}" class="member-checkbox w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-base font-medium text-gray-900">{{ $member->surname }}, {{ $member->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        @php
                                            $temporaryEmailDomain = \App\Models\Settings::getTemporaryEmailDomain();
                                            $isTemporaryEmail = $temporaryEmailDomain && str_contains($member->email, '@' . $temporaryEmailDomain);
                                        @endphp
                                        @if($isTemporaryEmail)
                                            <span class="text-amber-600 font-medium">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                {{ $member->email }}
                                            </span>
                                            <span class="ml-2 px-1 py-0.5 text-xs bg-amber-100 text-amber-800 rounded">
                                                Geçici
                                            </span>
                                        @else
                                            {{ $member->email }}
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $member->phone ?: 'Belirtilmemiş' }}</div>
                                <div class="text-sm text-gray-500">{{ $member->member_no }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $member->membership_date->format('d.m.Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $member->membership_date->diffForHumansTr() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ number_format($member->monthly_dues, 2) }} €</div>
                                <div class="text-sm text-gray-500">Aylık</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->status === 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Aktif
                                    </span>
                                @elseif($member->status === 'inactive')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-pause-circle mr-1"></i>
                                        Pasif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-ban mr-1"></i>
                                        Askıya Alınmış
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $overdueCount = $member->overdue_count;
                                @endphp
                                @if($overdueCount > 0)
                                    <div class="text-sm font-medium text-red-600">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $overdueCount }} gecikmiş aidat
                                    </div>
                                    @php
                                        $mostRecentUnpaid = $member->most_recent_unpaid_due;
                                    @endphp
                                    @if($mostRecentUnpaid)
                                        <div class="text-xs text-gray-500">
                                            Son: {{ $mostRecentUnpaid->month }}/{{ $mostRecentUnpaid->year }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-sm font-medium text-green-600">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Gecikme yok
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->privacy_consent)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Verildi
                                        @if($member->privacy_consent_date)
                                            <span class="ml-1">({{ $member->privacy_consent_date->format('d.m.Y') }})</span>
                                        @endif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Verilmedi
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.members.show', $member) }}"
                                       class="text-blue-600 hover:text-blue-900 p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200"
                                       title="Görüntüle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.members.edit', $member) }}"
                                       class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200"
                                       title="Düzenle">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('member.application.pdf', ['id' => $member->id]) }}" target="_blank"
                                       class="text-rose-600 hover:text-rose-900 p-2 rounded-lg hover:bg-rose-50 transition-colors duration-200"
                                       title="Başvuru Formu">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <a href="{{ route('admin.payments.create') }}?member_id={{ $member->id }}"
                                       class="text-green-600 hover:text-green-900 p-2 rounded-lg hover:bg-green-50 transition-colors duration-200"
                                       title="Ödeme Al">
                                        <i class="fas fa-credit-card"></i>
                                    </a>
                                    <button type="button" onclick="showDeleteModal({{ $member->id }}, '{{ $member->full_name }}')"
                                            class="text-red-600 hover:text-red-900 p-2 rounded-lg hover:bg-red-50 transition-colors duration-200"
                                            title="Sil">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">Henüz üye eklenmemiş</p>
                                    <p class="text-gray-400 text-sm mt-2">İlk üyeyi eklemek için "Yeni Üye Ekle" butonuna tıklayın.</p>
                                    <a href="{{ route('admin.members.create') }}" class="btn-primary text-white px-6 py-3 rounded-xl font-medium mt-4 inline-flex items-center">
                                        <i class="fas fa-plus mr-2"></i>
                                        İlk Üyeyi Ekle
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="text-sm text-gray-700">
                            Toplam <span class="font-semibold text-gray-900">{{ $members->total() }}</span> üyeden
                            <span class="font-semibold text-blue-600">{{ $members->firstItem() }}-{{ $members->lastItem() }}</span> arası gösteriliyor
                        </div>
                        <div class="hidden sm:block h-6 w-px bg-gray-300"></div>
                        <div class="px-3 py-1 bg-blue-600 text-white rounded-lg font-semibold text-sm shadow-md">
                            Sayfa {{ $members->currentPage() }} / {{ $members->lastPage() }}
                        </div>
                    </div>
                    <div class="flex items-center space-x-1">
                        {{-- First Page --}}
                        @if($members->onFirstPage())
                            <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-angle-double-left"></i>
                            </span>
                        @else
                            <a href="{{ $members->url(1) }}" class="px-3 py-2 text-gray-700 bg-white hover:bg-blue-50 border border-gray-300 rounded-lg transition-colors duration-200">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        @endif

                        {{-- Previous Page --}}
                        @if($members->onFirstPage())
                            <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $members->previousPageUrl() }}" class="px-3 py-2 text-gray-700 bg-white hover:bg-blue-50 border border-gray-300 rounded-lg transition-colors duration-200">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @php
                            $start = max($members->currentPage() - 2, 1);
                            $end = min($start + 4, $members->lastPage());
                            $start = max($end - 4, 1);
                        @endphp

                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $members->currentPage())
                                <span class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg shadow-lg transform scale-110 border-2 border-blue-700">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $members->url($page) }}" class="px-4 py-2 text-gray-700 bg-white hover:bg-blue-50 border border-gray-300 rounded-lg transition-all duration-200 hover:shadow-md">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Next Page --}}
                        @if($members->hasMorePages())
                            <a href="{{ $members->nextPageUrl() }}" class="px-3 py-2 text-gray-700 bg-white hover:bg-blue-50 border border-gray-300 rounded-lg transition-colors duration-200">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif

                        {{-- Last Page --}}
                        @if($members->currentPage() == $members->lastPage())
                            <span class="px-3 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                                <i class="fas fa-angle-double-right"></i>
                            </span>
                        @else
                            <a href="{{ $members->url($members->lastPage()) }}" class="px-3 py-2 text-gray-700 bg-white hover:bg-blue-50 border border-gray-300 rounded-lg transition-colors duration-200">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
// Bulk selection functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const memberCheckboxes = document.querySelectorAll('.member-checkbox');

    console.log('Select All checkbox:', selectAllCheckbox);
    console.log('Member checkboxes:', memberCheckboxes.length);

    if (selectAllCheckbox) {
        // Select all functionality - tüm sayfalardaki üyeleri seç
        selectAllCheckbox.addEventListener('change', function() {
            console.log('Select all changed:', this.checked);

            if (this.checked) {
                // Tüm sayfalardaki üyeleri seçmek için AJAX isteği gönder
                fetch('{{ route("admin.members.select-all") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'select_all',
                        filters: {
                            search: '{{ request('search') }}',
                            status: '{{ request('status') }}',
                            email_type: '{{ request('email_type') }}',
                            payment_method: '{{ request('payment_method') }}',
                            phone_presence: '{{ request('phone_presence') }}',
                            address_presence: '{{ request('address_presence') }}',
                            gender: '{{ request('gender') }}'
                        }
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mevcut sayfadaki tüm checkbox'ları seç
                        memberCheckboxes.forEach(checkbox => {
                            checkbox.checked = true;
                        });

                        // Başarı mesajı göster
                        showNotification(`Tüm sayfalardan ${data.count} üye seçildi`, 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Tüm üyeleri seçerken hata oluştu', 'error');
                });
            } else {
                // Tüm seçimleri kaldır
                memberCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        });
    }

    // Update select all when individual checkboxes change
    memberCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;
            const totalCount = memberCheckboxes.length;

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedCount === totalCount;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
            }
        });
    });
});

function generateLabels() {
    // Get selected member IDs
    const selectedMembers = Array.from(document.querySelectorAll('.member-checkbox:checked')).map(cb => cb.value);

    // Get current search and filter parameters
    const searchParams = new URLSearchParams(window.location.search);
    const search = searchParams.get('search') || '';
    const status = searchParams.get('status') || '';
    const email_type = searchParams.get('email_type') || '';
    const payment_method = searchParams.get('payment_method') || '';

    // Create form data
    const formData = new FormData();
    if (selectedMembers.length > 0) {
        formData.append('member_ids', selectedMembers.join(','));
    }
    if (search) formData.append('search', search);
    if (status) formData.append('status', status);
    if (email_type) formData.append('email_type', email_type);
    if (payment_method) formData.append('payment_method', payment_method);

    // Show loading message
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Hazırlanıyor...';
    button.disabled = true;

    // Send request
    fetch('{{ route("admin.members.generate-labels") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Etiket oluşturulurken hata oluştu');
    })
    .then(blob => {
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'uye-etiketleri-' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.pdf';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);

        // Show success message
        showNotification('Etiketler başarıyla oluşturuldu!', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Etiket oluşturulurken hata oluştu: ' + error.message, 'error');
    })
    .finally(() => {
        // Restore button
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function generateEnvelopes() {
    const selectedMembers = Array.from(document.querySelectorAll('.member-checkbox:checked')).map(cb => cb.value);
    const selectAllCheckbox = document.getElementById('selectAll');

    // Üye seçimi kontrolü
    if (selectedMembers.length === 0) {
        showNotification('Lütfen aşağıdan seçim yapınız', 'error');
        return;
    }

    const searchParams = new URLSearchParams(window.location.search);
    const search = searchParams.get('search') || '';
    const status = searchParams.get('status') || '';
    const email_type = searchParams.get('email_type') || '';
    const payment_method = searchParams.get('payment_method') || '';
    const phone_presence = searchParams.get('phone_presence') || '';
    const address_presence = searchParams.get('address_presence') || '';
    const gender = searchParams.get('gender') || '';

    const formData = new FormData();

    // Eğer "Tümünü Seç" aktifse, tüm sayfalardaki üyeleri al
    if (selectAllCheckbox && selectAllCheckbox.checked) {
        // Tüm sayfalardaki üyeleri almak için filtreleri gönder
        if (search) formData.append('search', search);
        if (status) formData.append('status', status);
        if (email_type) formData.append('email_type', email_type);
        if (payment_method) formData.append('payment_method', payment_method);
        if (phone_presence) formData.append('phone_presence', phone_presence);
        if (address_presence) formData.append('address_presence', address_presence);
        if (gender) formData.append('gender', gender);
    } else {
        // Sadece seçili üyeleri al
        if (selectedMembers.length > 0) {
            formData.append('member_ids', selectedMembers.join(','));
        }
        if (search) formData.append('search', search);
        if (status) formData.append('status', status);
        if (email_type) formData.append('email_type', email_type);
        if (payment_method) formData.append('payment_method', payment_method);
        if (phone_presence) formData.append('phone_presence', phone_presence);
        if (address_presence) formData.append('address_presence', address_presence);
        if (gender) formData.append('gender', gender);
    }

    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Hazırlanıyor...';
    button.disabled = true;

    fetch('{{ route("admin.members.generate-envelopes") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Zarf oluşturulurken hata oluştu');
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'zarf-' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.pdf';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);

        showNotification('Zarflar başarıyla oluşturuldu!', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Zarf oluşturulurken hata oluştu: ' + error.message, 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Silme Modal Fonksiyonları
function showDeleteModal(memberId, memberName) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const memberNameSpan = document.getElementById('memberName');

    memberNameSpan.textContent = memberName;
    form.action = `/admin/members/${memberId}`;
    modal.classList.remove('hidden');
}

function hideDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
}

// Modal dışına tıklayınca kapat
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});
</script>

<!-- Silme Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Üye Silme Onayı</h3>
                <button onclick="hideDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="mb-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <span class="text-sm text-red-800">
                            <strong id="memberName"></strong> adlı üyeyi silmek istediğinizden emin misiniz?
                        </span>
                    </div>
                </div>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label for="deletion_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Silme Nedeni <span class="text-red-500">*</span>
                        </label>
                        <textarea id="deletion_reason" name="deletion_reason" rows="3" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-300"
                                placeholder="Üyeyi neden sildiğinizi belirtin..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Silme nedeni zorunludur ve silinen üyeler bölümünde görüntülenecektir.</p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="hideDeleteModal()"
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

<!-- Bulk Receipt Modal -->
<div id="bulkReceiptModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden">
    <div class="absolute inset-0 flex items-center justify-center p-4" onclick="closeBulkReceiptModal()">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-5 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-xl mr-4">
                            <i class="fas fa-file-invoice text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Spendenbescheinigung Oluştur</h3>
                            <p class="text-purple-100 text-sm">Üye ödemeleri için toplu makbuz</p>
                        </div>
                    </div>
                    <button class="text-white/80 hover:text-white hover:bg-white/20 p-2 rounded-lg transition-all duration-200" onclick="closeBulkReceiptModal()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-6">
                <!-- Member Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-purple-600"></i>
                        Üye Seçimi
                    </label>
                    <div class="space-y-3">
                        <div class="relative">
                            <input type="text" id="memberSearch" placeholder="Üye ara..."
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 pl-12 bg-gray-50 focus:bg-white text-gray-800" />
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <div class="border-2 border-gray-200 rounded-xl bg-white shadow-sm max-h-48 overflow-y-auto">
                            <select id="bulkMember" size="6" class="w-full border-0 focus:ring-0 focus:outline-none text-gray-800">
                                @foreach($allMembers as $member)
                                    <option value="{{ $member->id }}" class="px-4 py-3 hover:bg-purple-50 cursor-pointer border-b border-gray-100 last:border-b-0">{{ $member->surname }} {{ $member->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                        <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                        Seçilen üyeye ait ödemelerden makbuz oluşturulur
                    </p>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>
                        Tarih Aralığı (Opsiyonel)
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-2">Başlangıç Tarihi</label>
                            <input type="date" id="bulkFrom" lang="tr"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-2">Bitiş Tarihi</label>
                            <input type="date" id="bulkTo" lang="tr"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium transition-all duration-200 flex items-center" onclick="closeBulkReceiptModal()">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </button>
                <a id="bulkReceiptGo" href="#" class="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-medium transition-all duration-200 flex items-center shadow-lg hover:shadow-xl">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Oluştur
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    async function openBulkReceiptModal() {
        document.getElementById('bulkReceiptModal').classList.remove('hidden');

        // Check first member's payment status
        const firstMemberId = document.getElementById('bulkMember').value;
        if (firstMemberId) {
            await updateBulkReceiptUrl();
        }
    }

    function closeBulkReceiptModal() {
        document.getElementById('bulkReceiptModal').classList.add('hidden');
    }

    // Check if member has payments
    async function checkMemberPayments(memberId) {
        try {
            const response = await fetch(`/admin/payments/check-payments/${memberId}`);
            const data = await response.json();
            return data.hasPayments;
        } catch (error) {
            console.error('Error checking payments:', error);
            return true; // Allow if check fails
        }
    }

    // Show payment warning
    function showPaymentWarning() {
        // Remove existing warning if any
        const existingWarning = document.getElementById('paymentWarning');
        if (existingWarning) {
            existingWarning.remove();
        }

        const warning = document.createElement('div');
        warning.id = 'paymentWarning';
        warning.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4';
        warning.innerHTML = `
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                <div>
                    <div class="text-sm font-semibold text-yellow-800 mb-1">
                        Ödeme Kaydı Bulunamadı
                    </div>
                    <div class="text-sm text-yellow-700">
                        Seçilen üyenin ödeme kaydı bulunmuyor. Spendenbescheinigung oluşturmak için önce bu üyeye ödeme kaydı eklemeniz gerekiyor.
                    </div>
                </div>
            </div>
        `;

        document.querySelector('#bulkReceiptModal .p-6').insertBefore(warning, document.querySelector('#bulkReceiptModal .p-6').firstChild);
    }

    // Update bulk receipt URL
    async function updateBulkReceiptUrl() {
        const memberId = document.getElementById('bulkMember').value;
        const fromDate = document.getElementById('bulkFrom').value;
        const toDate = document.getElementById('bulkTo').value;

        // Check if member has payments
        const hasPayments = await checkMemberPayments(memberId);

        if (!hasPayments) {
            showPaymentWarning();
            document.getElementById('bulkReceiptGo').style.display = 'none';
            return;
        } else {
            // Hide warning if exists
            const existingWarning = document.getElementById('paymentWarning');
            if (existingWarning) {
                existingWarning.remove();
            }
            document.getElementById('bulkReceiptGo').style.display = 'flex';
        }

        let url = `/admin/payments/bulk-receipt?member_id=${memberId}`;

        if (fromDate) {
            url += `&from=${fromDate}`;
        }
        if (toDate) {
            url += `&to=${toDate}`;
        }

        document.getElementById('bulkReceiptGo').href = url;
    }

    // Member search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const memberSearch = document.getElementById('memberSearch');
        const memberSelect = document.getElementById('bulkMember');

        if (memberSearch && memberSelect) {
            memberSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const options = memberSelect.querySelectorAll('option');

                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            });
        }

        // Update URL when member or dates change
        const bulkMember = document.getElementById('bulkMember');
        const bulkFrom = document.getElementById('bulkFrom');
        const bulkTo = document.getElementById('bulkTo');

        if (bulkMember) {
            bulkMember.addEventListener('change', updateBulkReceiptUrl);
        }
        if (bulkFrom) {
            bulkFrom.addEventListener('change', updateBulkReceiptUrl);
        }
        if (bulkTo) {
            bulkTo.addEventListener('change', updateBulkReceiptUrl);
        }
    });
</script>
@endsection
