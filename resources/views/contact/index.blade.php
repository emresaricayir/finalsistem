<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.contact') }} - {{ $settings['organization_name'] ?? 'Cami Üyelik Sistemi' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @include('partials.theme-styles')
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .elegant-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .nav-item {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--theme-link-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .nav-item:hover::before {
            transform: scaleY(1);
        }

        .nav-item:hover {
            background: rgba(13, 148, 136, 0.05);
            transform: translateX(4px);
        }

        .contact-card {
            transition: all 0.3s ease;
            border-left: 4px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .contact-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #0d9488, #14b8a6);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .contact-card:hover::before {
            transform: scaleX(1);
        }

        .contact-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-left-color: #0d9488;
        }

        .breadcrumb-separator {
            color: #94a3b8;
            margin: 0 8px;
        }

        .elegant-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 24px 0;
        }

        .sticky-sidebar {
            position: sticky;
            top: 2rem;
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
        }

        .modern-btn {
            background: linear-gradient(135deg, #0d9488, #14b8a6);
            color: white;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .modern-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .modern-btn:hover::before {
            left: 100%;
        }

        .modern-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(13, 148, 136, 0.3);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen">
    @include('partials.top-header')
    @include('partials.main-menu')

    <!-- Main Container -->
    <div class="relative min-h-screen">

        <!-- Top Navigation Bar -->
        <div class="relative elegant-card border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <!-- Breadcrumb -->
                    <nav class="flex items-center" aria-label="Breadcrumb">
                        <a href="/" class="text-sm font-medium text-gray-600 hover:text-teal-700 transition-colors duration-200">
                            {{ __('common.breadcrumb_home') }}
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="text-sm font-semibold text-teal-800">{{ __('common.contact') }}</span>
                    </nav>

                    <!-- Back Button -->
                    <a href="javascript:history.back()" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Left Sidebar - Hızlı Menü (Hidden on Mobile) -->
                <div class="hidden lg:block lg:col-span-1">
                    <div class="elegant-card rounded-xl p-6 sticky-sidebar">
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('common.quick_menu') }}</h2>
                            <div class="elegant-divider"></div>
                        </div>

                        <nav class="space-y-1">
                            <a href="/" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.home') }}
                            </a>

                            <a href="/duyurular" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.announcements') }}
                            </a>

                            <a href="/haberler" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.news') }}
                            </a>

                            <a href="/uyelik-basvuru" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.member_application') }}
                            </a>

                            <a href="/uye-giris" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.member_login') }}
                            </a>

                            <a href="/iletisim" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200 bg-teal-50 text-teal-700">
                                {{ __('common.contact') }}
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Right Content - İletişim Bilgileri -->
                <div class="lg:col-span-3">
                    <div class="elegant-card rounded-xl p-8">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-teal-800 mb-4">{{ __('common.contact_information') }}</h1>
                            <div class="elegant-divider"></div>
                        </div>

                        <!-- Contact Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Address -->
                            @if($settings['organization_address'])
                            <div class="contact-card bg-white rounded-lg shadow-sm overflow-hidden p-6 fade-in">
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                        <i class="fas fa-map-marker-alt text-red-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('common.address') }}</h3>
                                        <p class="text-gray-700 leading-relaxed">{{ $settings['organization_address'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Phone -->
                            @if($settings['organization_phone'])
                            <div class="contact-card bg-white rounded-lg shadow-sm overflow-hidden p-6 fade-in">
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                        <i class="fas fa-phone text-green-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('common.phone') }}</h3>
                                        <a href="tel:{{ $settings['organization_phone'] }}" class="text-gray-700 hover:text-teal-600 transition-colors text-lg font-medium">
                                            {{ $settings['organization_phone'] }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Email -->
                            @if($settings['organization_email'])
                            <div class="contact-card bg-white rounded-lg shadow-sm overflow-hidden p-6 fade-in">
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                        <i class="fas fa-envelope text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('common.email') }}</h3>
                                        <a href="mailto:{{ $settings['organization_email'] }}" class="text-gray-700 hover:text-teal-600 transition-colors text-lg font-medium break-all">
                                            {{ $settings['organization_email'] }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Fax -->
                            @if($settings['organization_fax'])
                            <div class="contact-card bg-white rounded-lg shadow-sm overflow-hidden p-6 fade-in">
                                <div class="flex items-start">
                                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                                        <i class="fas fa-fax text-purple-600 text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('common.fax') }}</h3>
                                        <p class="text-gray-700 text-lg font-medium">{{ $settings['organization_fax'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Banka Bilgileri Section -->
                        @if($settings['bank_name'] || $settings['account_holder'] || $settings['bank_iban'] || $settings['bank_bic'])
                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-8 mb-8">
                            <div class="text-center mb-6">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('common.bank_information') }}</h2>
                                <p class="text-gray-600">{{ __('common.bank_info_description') }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($settings['bank_name'])
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-university text-blue-600"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ __('common.bank_name') }}</h3>
                                    </div>
                                    <p class="text-gray-700 font-medium">{{ $settings['bank_name'] }}</p>
                                </div>
                                @endif

                                @if($settings['account_holder'])
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-green-600"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ __('common.account_holder') }}</h3>
                                    </div>
                                    <p class="text-gray-700 font-medium">{{ $settings['account_holder'] }}</p>
                                </div>
                                @endif

                                @if($settings['bank_iban'])
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-credit-card text-purple-600"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ __('common.iban') }}</h3>
                                    </div>
                                    <p class="text-gray-700 font-mono text-lg font-bold tracking-wider">{{ $settings['bank_iban'] }}</p>
                                </div>
                                @endif

                                @if($settings['bank_bic'])
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-code text-orange-600"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ __('common.bic_swift') }}</h3>
                                    </div>
                                    <p class="text-gray-700 font-mono text-lg font-bold">{{ $settings['bank_bic'] }}</p>
                                </div>
                                @endif
                            </div>

                        </div>
                        @endif

                        <!-- Quick Actions -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <a href="{{ route('member.application') }}" class="modern-btn px-6 py-4 rounded-xl font-semibold text-center block">
                                <i class="fas fa-user-plus mr-2"></i>
                                {{ __('common.apply_for_membership') }}
                            </a>

                            <a href="{{ route('member.login') }}" class="bg-white border-2 border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white px-6 py-4 rounded-xl font-semibold text-center transition-all duration-300 block">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                {{ __('common.login_to_member_panel') }}
                            </a>
                        </div>

                        <!-- Interactive Map Section -->
                        @if($settings['map_latitude'] && $settings['map_longitude'])
                        <div class="mt-8 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                            <div class="bg-gradient-to-r from-teal-600 to-blue-600 p-6">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fas fa-map-marker-alt text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">{{ __('common.our_location') }}</h3>
                                        <p class="text-teal-100">{{ __('common.you_can_see_our_location_on_map') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <div id="map" class="w-full h-96 bg-gray-100 rounded-b-xl"></div>
                                <div class="absolute top-4 right-4 bg-white rounded-lg shadow-lg p-3">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-map-marker-alt text-teal-600"></i>
                                        <span class="text-sm font-medium text-gray-700">{{ $settings['organization_name'] ?? __('common.location') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50 border-t border-gray-200">
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $settings['map_latitude'] }},{{ $settings['map_longitude'] }}"
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                            <i class="fas fa-directions mr-2"></i>
                                            {{ __('common.get_directions') }}
                                        </a>
                                        <a href="https://www.google.com/maps/search/?api=1&query={{ $settings['map_latitude'] }},{{ $settings['map_longitude'] }}"
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors">
                                            <i class="fas fa-external-link-alt mr-2"></i>
                                            {{ __('common.open_in_google_maps') }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('common.coordinates') }}: {{ $settings['map_latitude'] }}, {{ $settings['map_longitude'] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Map Placeholder when coordinates are not set -->
                        <div class="mt-8 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-400 to-gray-500 p-6">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fas fa-map-marker-alt text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">{{ __('common.map_location') }}</h3>
                                        <p class="text-gray-100">{{ __('common.coordinates_not_set') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-8 text-center">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-map text-4xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ __('common.map_location_not_set') }}</h4>
                                <p class="text-gray-600 mb-4">{{ __('common.map_location_description') }}</p>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="text-sm text-blue-800">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        {{ __('common.map_location_instruction') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Additional Information -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <!-- Leaflet Maps Script -->
    @if($settings['map_latitude'] && $settings['map_longitude'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize map when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Map coordinates
            const lat = {{ $settings['map_latitude'] }};
            const lng = {{ $settings['map_longitude'] }};
            const zoom = {{ $settings['map_zoom'] ?? 15 }};

            // Initialize map
            const map = L.map('map').setView([lat, lng], zoom);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(map);

            // Custom marker icon
            const customIcon = L.divIcon({
                className: 'custom-marker',
                html: `
                    <div style="
                        width: 40px;
                        height: 40px;
                        background: #0d9488;
                        border: 3px solid white;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                        position: relative;
                    ">
                        <div style="
                            width: 16px;
                            height: 16px;
                            background: white;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <div style="
                                width: 8px;
                                height: 8px;
                                background: #0d9488;
                                border-radius: 50%;
                            "></div>
                        </div>
                    </div>
                `,
                iconSize: [40, 40],
                iconAnchor: [20, 20],
                popupAnchor: [0, -20]
            });

            // Add marker
            const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

            // Popup content
            const popupContent = `
                <div style="padding: 10px; min-width: 200px;">
                    <h3 style="margin: 0 0 8px 0; font-weight: 600; color: #1f2937;">{{ $settings['organization_name'] ?? __('common.location') }}</h3>
                    @if($settings['organization_address'])
                    <p style="margin: 0 0 8px 0; font-size: 14px; color: #6b7280;">{{ $settings['organization_address'] }}</p>
                    @endif
                    @if($settings['organization_phone'])
                    <p style="margin: 0 0 12px 0; font-size: 14px; color: #6b7280;">
                        📞 <a href="tel:{{ $settings['organization_phone'] }}" style="color: #2563eb; text-decoration: none;">{{ $settings['organization_phone'] }}</a>
                    </p>
                    @endif
                    <div style="margin-top: 12px;">
                        <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}"
                           target="_blank"
                           style="
                               display: inline-flex;
                               align-items: center;
                               padding: 6px 12px;
                               background: #2563eb;
                               color: white;
                               text-decoration: none;
                               border-radius: 6px;
                               font-size: 12px;
                               font-weight: 500;
                           ">
                            🧭 {{ __('common.directions') }}
                        </a>
                    </div>
                </div>
            `;

            // Add popup to marker
            marker.bindPopup(popupContent).openPopup();

            // Add click event to marker
            marker.on('click', function() {
                this.openPopup();
            });
        });
    </script>
    @endif
</body>
</html>
