@extends('admin.layouts.app')

@section('title', 'Sistem Ayarları')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                <i class="fas fa-cog mr-2 text-blue-500"></i>
                Sistem Ayarları
            </h1>
            <p class="mt-2 text-gray-600">Cami derneği bilgilerini ve sistem ayarlarını yönetin.</p>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 alert-success">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Settings Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Organization Information -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-building mr-2 text-blue-500"></i>
                    Dernek Bilgileri
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="organization_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-2 text-blue-500"></i>
                            Dernek Adı *
                        </label>
                        <input type="text" name="settings[organization_name]" id="organization_name"
                               value="{{ old('settings.organization_name', \App\Models\Settings::get('organization_name', 'Dernek Adı')) }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Cami Derneği Adı">
                    </div>

                    <div>
                        <label for="organization_subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-red-500"></i>
                            Alt Başlık (Opsiyonel)
                        </label>
                        <input type="text" name="settings[organization_subtitle]" id="organization_subtitle"
                               value="{{ old('settings.organization_subtitle', \App\Models\Settings::get('organization_subtitle')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Türkisch Islamische Gemeinde zu ...">
                        <p class="text-xs text-gray-500 mt-1">Üstte kırmızı ve küçük (opsiyonel).</p>
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2 text-purple-500"></i>
                            Dernek Logosu
                        </label>
                        <div class="space-y-3">
                            @if(\App\Models\Settings::hasLogo())
                                <div class="flex items-center space-x-3">
                                    <img src="{{ \App\Models\Settings::getLogoUrl() }}"
                                         alt="Mevcut Logo"
                                         class="w-24 h-24 object-contain border border-gray-200 rounded-lg bg-white">
                                    <div class="text-sm text-gray-600">
                                        <p class="font-medium">Mevcut Logo</p>
                                        <p class="text-xs">{{ \App\Models\Settings::get('logo') }}</p>
                                        <p class="text-xs text-green-600">✓ Yüklendi</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center space-x-3">
                                    <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <p class="font-medium">Logo Yok</p>
                                        <p class="text-xs">Henüz logo yüklenmemiş</p>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="logo" id="logo" accept="image/*"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                PNG, JPG, JPEG formatları. Maksimum 2MB. Önerilen boyut: 200x200px
                            </p>
                        </div>
                    </div>

                    <div>
                        <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-star mr-2 text-orange-500"></i>
                            Site Favicon'u
                        </label>
                        <div class="space-y-3">
                            @if(\App\Models\Settings::hasFavicon())
                                <div class="flex items-center space-x-3">
                                    <img src="{{ \App\Models\Settings::getFaviconUrl() }}"
                                         alt="Mevcut Favicon"
                                         class="w-16 h-16 object-contain border border-gray-200 rounded-lg bg-white">
                                    <div class="text-sm text-gray-600">
                                        <p class="font-medium">Mevcut Favicon</p>
                                        <p class="text-xs">{{ \App\Models\Settings::get('favicon') }}</p>
                                        <p class="text-xs text-green-600">✓ Yüklendi</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center space-x-3">
                                    <div class="w-16 h-16 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-star text-gray-400 text-xl"></i>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <p class="font-medium">Favicon Yok</p>
                                        <p class="text-xs">Henüz favicon yüklenmemiş</p>
                                    </div>
                                </div>
                            @endif
                            <input type="file" name="favicon" id="favicon" accept="image/*,.ico"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                PNG, ICO, JPEG formatları. Maksimum 1MB. Önerilen boyut: 32x32px veya 16x16px
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Bilgilendirme -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-semibold text-blue-800 mb-1">
                                Spendebescheinigung ve Seçim Belgesi için doldurmanız gereken bilgilerBilgileri
                            </h4>
                            <p class="text-sm text-blue-700">
                                Bu bölümdeki bilgiler Spendebescheinigung ve Seçim belgesi için kullanılır.
                                Belge bilgileri bu bölümden çekmektedir. Lütfen tüm alanları doldurunuz.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Verein Bilgileri - Üst Bölüm -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="founding_year" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-plus mr-2 text-blue-500"></i>
                            Gründ. Jahr
                        </label>
                        <input type="number" name="settings[founding_year]" id="founding_year"
                               value="{{ old('settings.founding_year', \App\Models\Settings::get('founding_year')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="2020" min="1900" max="{{ date('Y') }}">
                        <p class="text-xs text-gray-500 mt-1">Derneğin kuruluş yılı</p>
                    </div>

                    <div>
                        <label for="court_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-gavel mr-2 text-green-500"></i>
                            Amtsgericht
                        </label>
                        <input type="text" name="settings[court_name]" id="court_name"
                               value="{{ old('settings.court_name', \App\Models\Settings::get('court_name')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Amtsgericht stadtname">
                        <p class="text-xs text-gray-500 mt-1">Kayıtlı olduğu mahkeme adı</p>
                    </div>

                    <div>
                        <label for="association_register" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-book mr-2 text-purple-500"></i>
                            Vereinsregister
                        </label>
                        <input type="text" name="settings[association_register]" id="association_register"
                               value="{{ old('settings.association_register', \App\Models\Settings::get('association_register')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="VR 1234">
                        <p class="text-xs text-gray-500 mt-1">Dernek kayıt numarası</p>
                    </div>
                </div>

                <!-- Finanzamt Bilgileri - Alt Bölüm -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-invoice mr-2 text-purple-500"></i>
                            Finanzamt
                        </label>
                        <input type="text" name="settings[tax_number]" id="tax_number"
                               value="{{ old('settings.tax_number', \App\Models\Settings::get('tax_number', 'Hannover Land II')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Hannover Land II">
                    </div>

                    <div>
                        <label for="stnr_number" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hashtag mr-2 text-orange-500"></i>
                            STNR
                        </label>
                        <input type="text" name="settings[stnr_number]" id="stnr_number"
                               value="{{ old('settings.stnr_number', \App\Models\Settings::get('stnr_number', '27/209/02246')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="27/209/02246">
                        <p class="text-xs text-gray-500 mt-1">Steuernummer</p>
                    </div>

                    <div>
                        <label for="tax_office_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2 text-green-500"></i>
                            Finanzamt Tarihi
                        </label>
                        <input type="date" name="settings[tax_office_date]" id="tax_office_date"
                               value="{{ old('settings.tax_office_date', \App\Models\Settings::get('tax_office_date', '2021-11-11')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-address-book mr-2 text-green-500"></i>
                    İletişim Bilgileri
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="organization_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-blue-500"></i>
                            Telefon *
                        </label>
                        <input type="tel" name="settings[organization_phone]" id="organization_phone"
                               value="{{ old('settings.organization_phone', \App\Models\Settings::get('organization_phone')) }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="+49 555 123 45 67">
                    </div>

                    <div>
                        <label for="organization_fax" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-fax mr-2 text-purple-500"></i>
                            Faks
                        </label>
                        <input type="tel" name="settings[organization_fax]" id="organization_fax"
                               value="{{ old('settings.organization_fax', \App\Models\Settings::get('organization_fax')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="+49 555 123 45 68">
                    </div>

                    <div>
                        <label for="organization_email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-green-500"></i>
                            E-posta
                        </label>
                        <input type="email" name="settings[organization_email]" id="organization_email"
                               value="{{ old('settings.organization_email', \App\Models\Settings::get('organization_email')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="info@camidernegi.com">
                    </div>

                    <div class="md:col-span-2">
                        <label for="organization_address" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                            Adres *
                        </label>
                        <textarea name="settings[organization_address]" id="organization_address" rows="3" required
                                  class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Dernek adresi...">{{ old('settings.organization_address', \App\Models\Settings::get('organization_address')) }}</textarea>
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-globe mr-2 text-purple-500"></i>
                            Web Sitesi
                        </label>
                        <input type="url" name="settings[website]" id="website"
                               value="{{ old('settings.website', \App\Models\Settings::get('website')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://www.camidernegi.com">
                    </div>
                </div>

                <!-- Harita Ayarları -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                        Harita Konumu
                    </h4>

                    <!-- Otomatik Koordinat Bulma -->
                    <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-start">
                            <i class="fas fa-magic text-green-600 mt-1 mr-3"></i>
                            <div class="flex-1">
                                <h5 class="text-sm font-semibold text-green-900 mb-2">Otomatik Koordinat Bulma</h5>
                                <p class="text-sm text-green-800 mb-3">Adresinizi yazın, koordinatları otomatik olarak bulalım!</p>
                                <div class="flex gap-3">
                                    <input type="text" id="address_lookup"
                                           placeholder="Örn: Musterstraße 123, 12345 Musterstadt, Almanya"
                                           class="flex-1 px-3 py-2 border border-green-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <button type="button" id="find_coordinates"
                                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition-colors">
                                        <i class="fas fa-search mr-1"></i>
                                        Koordinat Bul
                                    </button>
                                </div>
                                <div id="geocoding_status" class="mt-2 text-xs hidden"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="map_latitude" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map mr-2 text-blue-500"></i>
                                Enlem (Latitude)
                            </label>
                            <input type="text" name="settings[map_latitude]" id="map_latitude"
                                   value="{{ old('settings.map_latitude', \App\Models\Settings::get('map_latitude')) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="52.2025">
                            <p class="text-xs text-gray-500 mt-1">Otomatik bulunur veya manuel girilir</p>
                        </div>

                        <div>
                            <label for="map_longitude" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map mr-2 text-green-500"></i>
                                Boylam (Longitude)
                            </label>
                            <input type="text" name="settings[map_longitude]" id="map_longitude"
                                   value="{{ old('settings.map_longitude', \App\Models\Settings::get('map_longitude')) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="8.2014">
                            <p class="text-xs text-gray-500 mt-1">Otomatik bulunur veya manuel girilir</p>
                        </div>

                        <div>
                            <label for="map_zoom" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-search-plus mr-2 text-purple-500"></i>
                                Yakınlaştırma
                            </label>
                            <input type="number" name="settings[map_zoom]" id="map_zoom" min="1" max="20"
                                   value="{{ old('settings.map_zoom', \App\Models\Settings::get('map_zoom', '15')) }}"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="15">
                            <p class="text-xs text-gray-500 mt-1">1-20 arası değer (15 önerilen)</p>
                        </div>
                    </div>

                    <!-- Manuel Koordinat Girişi Bilgisi -->
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                            <div>
                                <h5 class="text-sm font-semibold text-blue-900 mb-1">Manuel Koordinat Girişi</h5>
                                <p class="text-sm text-blue-800">
                                    Yukarıdaki otomatik arama çalışmazsa:<br>
                                    1. <a href="https://maps.google.com" target="_blank" class="underline">Google Maps</a>'e gidin<br>
                                    2. Konumunuzu arayın ve işaretleyin<br>
                                    3. İşaretçiye sağ tıklayın ve "Koordinatları kopyala" seçin<br>
                                    4. Kopyalanan değerleri yukarıdaki alanlara yapıştırın
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-share-alt mr-2 text-purple-500"></i>
                    Sosyal Medya
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-facebook mr-2 text-blue-600"></i>
                            Facebook URL
                        </label>
                        <input type="url" name="settings[facebook_url]" id="facebook_url"
                               value="{{ old('settings.facebook_url', \App\Models\Settings::get('facebook_url')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://www.facebook.com/sayfa-adi">
                        <p class="text-xs text-gray-500 mt-1">Facebook sayfanızın tam URL adresini girin</p>
                    </div>

                    <div>
                        <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-instagram mr-2 text-pink-500"></i>
                            Instagram URL
                        </label>
                        <input type="url" name="settings[instagram_url]" id="instagram_url"
                               value="{{ old('settings.instagram_url', \App\Models\Settings::get('instagram_url')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://www.instagram.com/kullanici-adi">
                        <p class="text-xs text-gray-500 mt-1">Instagram profilinizin tam URL adresini girin</p>
                    </div>

                    <div>
                        <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-twitter mr-2 text-blue-400"></i>
                            Twitter URL
                        </label>
                        <input type="url" name="settings[twitter_url]" id="twitter_url"
                               value="{{ old('settings.twitter_url', \App\Models\Settings::get('twitter_url')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://www.twitter.com/kullanici-adi">
                        <p class="text-xs text-gray-500 mt-1">Twitter profilinizin tam URL adresini girin</p>
                    </div>

                    <div>
                        <label for="youtube_url" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-youtube mr-2 text-red-500"></i>
                            YouTube URL
                        </label>
                        <input type="url" name="settings[youtube_url]" id="youtube_url"
                               value="{{ old('settings.youtube_url', \App\Models\Settings::get('youtube_url')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://www.youtube.com/channel/kanal-id">
                        <p class="text-xs text-gray-500 mt-1">YouTube kanalınızın tam URL adresini girin</p>
                    </div>

                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-whatsapp mr-2 text-green-500"></i>
                            WhatsApp Numarası
                        </label>
                        <input type="tel" name="settings[whatsapp_number]" id="whatsapp_number"
                               value="{{ old('settings.whatsapp_number', \App\Models\Settings::get('whatsapp_number')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="+495551234567">
                        <p class="text-xs text-gray-500 mt-1">WhatsApp numaranızı uluslararası formatta girin (örn: +495551234567)</p>
                    </div>
                </div>
            </div>

            <!-- Bank Information -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-university mr-2 text-green-600"></i>
                    Banka Bilgileri
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="bank_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-2 text-blue-500"></i>
                            Banka Adı *
                        </label>
                        <input type="text" name="settings[bank_name]" id="bank_name"
                               value="{{ old('settings.bank_name', \App\Models\Settings::get('bank_name')) }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Deutsche Bank, Sparkasse vb.">
                    </div>

                    <div>
                        <label for="account_holder" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-green-500"></i>
                            Hesap Sahibi *
                        </label>
                        <input type="text" name="settings[account_holder]" id="account_holder"
                               value="{{ old('settings.account_holder', \App\Models\Settings::get('account_holder')) }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Dernek adı">
                    </div>

                    <div>
                        <label for="bank_iban" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-credit-card mr-2 text-purple-500"></i>
                            IBAN *
                        </label>
                        <input type="text" name="settings[bank_iban]" id="bank_iban"
                               value="{{ old('settings.bank_iban', \App\Models\Settings::get('bank_iban')) }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="DE89 3704 0044 0532 0130 00"
                               maxlength="34" oninput="formatIban(this)">
                    </div>

                    <div>
                        <label for="bank_bic" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-code mr-2 text-orange-500"></i>
                            BIC / SWIFT Kodu
                        </label>
                        <input type="text" name="settings[bank_bic]" id="bank_bic"
                               value="{{ old('settings.bank_bic', \App\Models\Settings::get('bank_bic')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="COBADEFFXXX"
                               maxlength="11">
                    </div>

                    <div class="md:col-span-2">
                        <label for="bank_purpose" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2 text-indigo-500"></i>
                            Varsayılan Ödeme Açıklaması
                        </label>
                        <input type="text" name="settings[bank_purpose]" id="bank_purpose"
                               value="{{ old('settings.bank_purpose', \App\Models\Settings::get('bank_purpose', 'Aidat Ödemesi')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Aidat Ödemesi, Bağış vb."
                               maxlength="140">
                        <p class="text-xs text-gray-500 mt-1">Ödemelerde kullanılacak varsayılan açıklama metni</p>
                    </div>

                    <div>
                        <label for="paypal_link" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-paypal mr-2 text-blue-500"></i>
                            PayPal Bağış Linki
                        </label>
                        <input type="url" name="settings[paypal_link]" id="paypal_link"
                               value="{{ old('settings.paypal_link', \App\Models\Settings::get('paypal_link')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://www.paypal.com/donate/?hosted_button_id=...">
                        <p class="text-xs text-gray-500 mt-1">PayPal bağış sayfası linki (isteğe bağlı)</p>
                    </div>

                    <div>
                        <label for="minimum_monthly_dues" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-euro-sign mr-2 text-green-500"></i>
                            Minimum Aylık Aidat (€)
                        </label>
                        <input type="number" name="settings[minimum_monthly_dues]" id="minimum_monthly_dues"
                               value="{{ old('settings.minimum_monthly_dues', \App\Models\Settings::get('minimum_monthly_dues', '5')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="5" min="1" step="1">
                        <p class="text-xs text-gray-500 mt-1">Üyelik başvurusunda minimum aidat miktarı. Seçenekler bu değerin 5'er artışı şeklinde oluşturulur.</p>
                    </div>

                </div>
            </div>

            <!-- PDF İmza Bölümü İsimleri -->
            <div class="bg-gray-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">
                    <i class="fas fa-signature mr-2 text-purple-500"></i>
                    PDF İmza Bölümü İsimleri
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="pdf_secretary_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-tie mr-2 text-blue-500"></i>
                            Sekreter İmza Adı
                        </label>
                        <input type="text" name="settings[pdf_secretary_name]" id="pdf_secretary_name"
                               value="{{ old('settings.pdf_secretary_name', \App\Models\Settings::get('pdf_secretary_name')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Sekreter Adı">
                        <p class="text-xs text-gray-500 mt-1">PDF imza bölümünde sekreter için gösterilecek isim</p>
                    </div>

                    <div>
                        <label for="pdf_accountant_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calculator mr-2 text-green-500"></i>
                            Muhasip İmza Adı
                        </label>
                        <input type="text" name="settings[pdf_accountant_name]" id="pdf_accountant_name"
                               value="{{ old('settings.pdf_accountant_name', \App\Models\Settings::get('pdf_accountant_name')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Muhasip Adı">
                        <p class="text-xs text-gray-500 mt-1">PDF imza bölümünde muhasip için gösterilecek isim</p>
                    </div>

                    <div>
                        <label for="pdf_vice_president_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user-shield mr-2 text-orange-500"></i>
                            Başkan Yardımcısı İmza Adı
                        </label>
                        <input type="text" name="settings[pdf_vice_president_name]" id="pdf_vice_president_name"
                               value="{{ old('settings.pdf_vice_president_name', \App\Models\Settings::get('pdf_vice_president_name')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Başkan Yardımcısı Adı">
                        <p class="text-xs text-gray-500 mt-1">PDF imza bölümünde başkan yardımcısı için gösterilecek isim</p>
                    </div>

                    <div>
                        <label for="pdf_president_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-crown mr-2 text-yellow-500"></i>
                            Başkan İmza Adı
                        </label>
                        <input type="text" name="settings[pdf_president_name]" id="pdf_president_name"
                               value="{{ old('settings.pdf_president_name', \App\Models\Settings::get('pdf_president_name')) }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Başkan Adı">
                        <p class="text-xs text-gray-500 mt-1">PDF imza bölümünde başkan için gösterilecek isim</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-times mr-2"></i>
                    İptal
                </a>
                <button type="submit" class="btn-primary text-white px-8 py-3 rounded-xl font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Ayarları Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Format IBAN with spaces
    function formatIban(input) {
        // Remove all spaces and convert to uppercase
        let value = input.value.replace(/\s/g, '').toUpperCase();

        // Add spaces every 4 characters
        let formatted = value.replace(/(.{4})/g, '$1 ').trim();

        // Update input value
        input.value = formatted;
    }

    // Format BIC to uppercase
    document.getElementById('bank_bic').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Success message auto-hide
    // Geocoding functionality
    document.addEventListener('DOMContentLoaded', function() {
        const findCoordinatesBtn = document.getElementById('find_coordinates');
        const addressInput = document.getElementById('address_lookup');
        const latitudeInput = document.getElementById('map_latitude');
        const longitudeInput = document.getElementById('map_longitude');
        const statusDiv = document.getElementById('geocoding_status');

        if (findCoordinatesBtn && addressInput) {
            findCoordinatesBtn.addEventListener('click', function() {
                const address = addressInput.value.trim();

                if (!address) {
                    showStatus('Lütfen bir adres girin!', 'error');
                    return;
                }

                showStatus('Koordinatlar aranıyor...', 'loading');
                findCoordinatesBtn.disabled = true;
                findCoordinatesBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Aranıyor...';

                // OpenStreetMap Nominatim API kullanarak ücretsiz geocoding
                const encodedAddress = encodeURIComponent(address);
                const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodedAddress}&limit=1&addressdetails=1`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const result = data[0];
                            const lat = parseFloat(result.lat);
                            const lon = parseFloat(result.lon);

                            latitudeInput.value = lat.toFixed(6);
                            longitudeInput.value = lon.toFixed(6);

                            showStatus(`✅ Koordinatlar bulundu: ${lat.toFixed(6)}, ${lon.toFixed(6)}`, 'success');

                            // Adres bilgisini de göster
                            if (result.display_name) {
                                showStatus(`📍 Bulunan konum: ${result.display_name}`, 'info');
                            }
                        } else {
                            showStatus('❌ Adres bulunamadı. Lütfen daha detaylı bir adres girin.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Geocoding error:', error);
                        showStatus('❌ Koordinat bulunurken hata oluştu. Lütfen tekrar deneyin.', 'error');
                    })
                    .finally(() => {
                        findCoordinatesBtn.disabled = false;
                        findCoordinatesBtn.innerHTML = '<i class="fas fa-search mr-1"></i>Koordinat Bul';
                    });
            });

            // Enter tuşu ile arama
            addressInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    findCoordinatesBtn.click();
                }
            });
        }

        function showStatus(message, type) {
            if (!statusDiv) return;

            statusDiv.className = 'mt-2 text-xs';
            statusDiv.classList.remove('hidden');

            switch(type) {
                case 'success':
                    statusDiv.classList.add('text-green-600');
                    break;
                case 'error':
                    statusDiv.classList.add('text-red-600');
                    break;
                case 'loading':
                    statusDiv.classList.add('text-blue-600');
                    break;
                case 'info':
                    statusDiv.classList.add('text-gray-600');
                    break;
            }

            statusDiv.textContent = message;

            // 5 saniye sonra gizle
            setTimeout(() => {
                statusDiv.classList.add('hidden');
            }, 5000);
        }
    });

    @if(session('success'))
        setTimeout(function() {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    @endif
</script>
@endsection
