<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.announcements') }} - {{ $orgName }}</title>
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
            background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.05);
            transform: translateX(4px);
        }

        .announcement-card {
            transition: all 0.3s ease;
            border-left: 4px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .announcement-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--theme-gradient);
        }


        .announcement-card:hover::before {
            transform: scaleX(1);
        }

        .announcement-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-left-color: var(--theme-link-color);
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

        .pagination-btn {
            transition: all 0.2s ease;
        }

        .pagination-btn:hover {
            background: #0d9488;
            color: white;
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

        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }

        @keyframes modalEnter {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .main-content {
                padding: 0.5rem;
            }

            .announcement-card {
                padding: 1rem !important;
            }

            .announcement-title {
                font-size: 1rem !important;
                line-height: 1.3;
                margin-bottom: 0.5rem;
            }

            .announcement-date {
                font-size: 0.75rem;
                margin-bottom: 0.75rem;
            }

            .announcement-image {
                width: 4rem !important;
                height: 4rem !important;
            }

            .devam-button {
                padding: 0.5rem 1rem !important;
                font-size: 0.75rem !important;
            }

            .pagination-btn {
                width: 2.5rem !important;
                height: 2.5rem !important;
                font-size: 0.75rem !important;
            }

            .breadcrumb {
                flex-wrap: wrap;
                gap: 0.125rem;
                font-size: 0.75rem;
            }

            .back-button {
                width: 2.5rem;
                height: 2.5rem;
                padding: 0.5rem;
                font-size: 0.75rem;
            }

            .elegant-card {
                padding: 1rem !important;
            }

            .announcement-content {
                flex-direction: column;
                gap: 0.75rem;
            }

            .announcement-text {
                flex: 1;
                min-width: 0;
            }

            .announcement-actions {
                align-self: flex-end;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body class="min-h-screen">

    @include('partials.header-menu-wrapper')

    <!-- Main Container -->
    <div class="relative min-h-screen">

        <!-- Top Navigation Bar -->
        <div class="relative elegant-card border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
            <!-- Breadcrumb -->
                    <nav class="flex items-center breadcrumb" aria-label="Breadcrumb">
                        <a href="/" class="text-sm font-medium text-gray-600 hover:text-teal-700 transition-colors duration-200">
                            Ana Sayfa
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="text-sm font-semibold text-teal-800">Duyurular</span>
            </nav>

                    <!-- Back Button -->
                    <a href="javascript:history.back()" class="inline-flex items-center justify-center w-8 h-8 bg-teal-50 hover:bg-teal-100 text-teal-700 rounded-full border border-teal-200 transition-all duration-200 hover:shadow-md back-button" title="Geri Dön">
                        <i class="fas fa-arrow-left text-sm"></i>
                    </a>
                </div>
            </div>
        </div>

    <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12 main-content">
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

                            <a href="/duyurular" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200 bg-teal-50 text-teal-700">
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

                            <a href="/iletisim" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.contact') }}
                            </a>
                        </nav>
                                    </div>
                                </div>

                <!-- Right Content - Duyurular -->
                <div class="lg:col-span-3">
                    <div class="elegant-card rounded-xl p-8">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-teal-800 mb-4">{{ __('common.announcements') }}</h1>
                            <div class="elegant-divider"></div>
                            </div>

        @if($announcements->count() > 0)
                            <div class="space-y-4">
                                @foreach($announcements as $index => $announcement)
                                    <article class="announcement-card bg-white rounded-lg shadow-sm overflow-hidden p-6 fade-in" style="animation-delay: {{ $index * 0.1 }}s;">
                                        <div class="flex items-start gap-4 announcement-content">
                                            <!-- Cover Image -->
                                            @if($announcement->image_url)
                                                <div class="flex-shrink-0">
                                                    <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" class="w-24 h-24 rounded-lg object-cover border border-gray-200 announcement-image">
                                                </div>
                                            @endif

                                            <div class="flex-1 min-w-0 announcement-text">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                        <!-- Title -->
                                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 leading-tight announcement-title">
                                            {{ $announcement->title }}
                                        </h3>

                                                        <!-- Date -->
                                                        <p class="text-sm text-gray-500 mb-3 announcement-date">
                                                            {{ $announcement->created_at->format('d F Y, H:i') }}
                                                        </p>
                                    </div>

                                                    <!-- DEVAMI Button -->
                                                    <div class="ml-4 flex-shrink-0 announcement-actions">
                                                        <a href="{{ route('announcements.detail', $announcement->id) }}" class="modern-btn px-4 py-2 rounded-lg font-medium text-sm inline-block devam-button">
                                                            {{ __('common.more') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Hidden full content for modal -->
                    <div id="announcement-detail-{{ $announcement->id }}" class="hidden">
                        <div class="announcement-full-data"
                             data-id="{{ $announcement->id }}"
                             data-title="{{ $announcement->title }}"
                             data-content="{{ $announcement->content }}"
                             data-type="{{ $announcement->type }}"
                             data-type-display="{{ $announcement->type_display }}"
                             data-date="{{ $announcement->created_at->format('d.m.Y H:i') }}"
                             data-featured="{{ $announcement->is_featured ? '1' : '0' }}"
                             data-obituary-name="{{ addslashes($announcement->obituary_name ?? '') }}"
                             data-obituary-date="{{ $announcement->obituary_date ? $announcement->obituary_date->format('d.m.Y') : '' }}"
                             data-funeral-time="{{ $announcement->funeral_time ? $announcement->funeral_time->format('H:i') : '' }}"
                             data-funeral-place="{{ addslashes($announcement->funeral_place ?? '') }}"
                             data-burial-place="{{ addslashes($announcement->burial_place ?? '') }}"
                             data-president-name="{{ \App\Models\Settings::get('pdf_president_name', 'Dernek Başkanı') }}"
                             data-image-url="{{ $announcement->image_url ?? '' }}">
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($announcements->hasPages())
                                <div class="mt-12 flex justify-center">
                                    <div class="flex items-center space-x-2">
                                        @if($announcements->currentPage() > 1)
                                            <a href="{{ $announcements->previousPageUrl() }}" class="pagination-btn w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                                                Önceki
                                            </a>
                                        @endif

                                        @for($i = 1; $i <= $announcements->lastPage(); $i++)
                                            @if($i <= 3 || $i > $announcements->lastPage() - 3 || abs($i - $announcements->currentPage()) <= 1)
                                                <a href="{{ $announcements->url($i) }}" class="pagination-btn w-10 h-10 rounded-lg {{ $announcements->currentPage() == $i ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center font-medium">
                                                    {{ $i }}
                                                </a>
                                            @elseif($i == 4 || $i == $announcements->lastPage() - 3)
                                                <span class="text-gray-400">...</span>
                                            @endif
                                        @endfor

                                        @if($announcements->currentPage() < $announcements->lastPage())
                                            <a href="{{ $announcements->nextPageUrl() }}" class="pagination-btn w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                                                Sonraki
                                            </a>
                                        @endif
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <span class="text-4xl text-gray-400">📢</span>
                    </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('common.no_announcements') }}</h3>
                    <p class="text-gray-600 mb-8 leading-relaxed">{{ __('common.no_content') }}</p>
                                <a href="/" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                    <i class="fas fa-home mr-2"></i>{{ __('common.back_to', ['item' => __('common.home')]) }}
                </a>
            </div>
        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Announcement Detail Modal -->
    <div id="announcement-modal" class="fixed inset-0 bg-black/60 backdrop-blur-md z-[10003] hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div id="modal-content" class="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-100 modal-enter">
                <div class="p-8">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <span id="modal-type-badge" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold mr-4 shadow-sm">
                                <span id="modal-type-text"></span>
                            </span>
                            <span id="modal-featured-badge" class="hidden inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-500 to-purple-600 text-white shadow-sm">
                                {{ __('common.featured') }}
                            </span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <!-- Share Dropdown -->
                            <div class="relative group">
                                <button id="modal-share-btn"
                                    class="text-slate-400 hover:text-teal-600 transition-all duration-200 p-3 rounded-xl hover:bg-teal-50 hover:shadow-sm">
                                <i class="fas fa-share-alt text-lg"></i>
                            </button>

                                <!-- Share Dropdown Menu -->
                                <div id="share-dropdown" class="absolute right-0 top-full mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                    <div class="p-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Paylaş</h4>
                                        <div class="grid grid-cols-2 gap-2">
                                            <!-- WhatsApp -->
                                            <button onclick="shareToWhatsApp()"
                                                    class="flex items-center justify-center p-3 rounded-lg bg-green-500 hover:bg-green-600 text-white transition-all duration-200 transform hover:scale-105">
                                                <i class="fab fa-whatsapp text-lg mr-2"></i>
                                                <span class="text-sm font-medium">WhatsApp</span>
                                            </button>

                                            <!-- Facebook -->
                                            <button onclick="shareToFacebook()"
                                                    class="flex items-center justify-center p-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition-all duration-200 transform hover:scale-105">
                                                <i class="fab fa-facebook-f text-lg mr-2"></i>
                                                <span class="text-sm font-medium">Facebook</span>
                                            </button>

                                            <!-- Twitter -->
                                            <button onclick="shareToTwitter()"
                                                    class="flex items-center justify-center p-3 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition-all duration-200 transform hover:scale-105">
                                                <i class="fab fa-twitter text-lg mr-2"></i>
                                                <span class="text-sm font-medium">Twitter</span>
                                            </button>

                                            <!-- Instagram -->
                                            <button onclick="shareToInstagram()"
                                                    class="flex items-center justify-center p-3 rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white transition-all duration-200 transform hover:scale-105">
                                                <i class="fab fa-instagram text-lg mr-2"></i>
                                                <span class="text-sm font-medium">Instagram</span>
                                            </button>

                                            <!-- Copy Link -->
                                            <button onclick="copyAnnouncementLink()"
                                                    class="flex items-center justify-center p-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white transition-all duration-200 transform hover:scale-105 col-span-2">
                                                <i class="fas fa-link text-lg mr-2"></i>
                                                <span class="text-sm font-medium">Linki Kopyala</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button onclick="closeAnnouncementModal()" class="text-slate-400 hover:text-slate-600 transition-all duration-200 p-3 rounded-xl hover:bg-slate-50 hover:shadow-sm">
                                Kapat
                            </button>
                        </div>
                    </div>

                    <!-- Logo and Organization Name -->
                    <div class="text-center mb-6">
                        @if(\App\Models\Settings::hasLogo())
                            <img src="{{ \App\Models\Settings::getLogoUrl() }}" alt="{{ \App\Models\Settings::get('organization_name', 'Organizasyon') }}" class="w-20 h-20 mx-auto object-contain mb-1">
                        @else
                            <div class="w-20 h-20 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-1">
                                <i class="fas fa-mosque text-3xl text-slate-400"></i>
                            </div>
                        @endif
                        <h3 class="text-lg font-semibold text-gray-900">{{ \App\Models\Settings::get('organization_name', 'Organizasyon') }}</h3>
                    </div>

                    <!-- Modal Content -->
                    <div>
                        <!-- Cover Image -->
                        <div id="modal-cover-image" class="mb-6 hidden">
                            <img id="modal-cover-img" src="" alt="" class="w-full h-64 object-cover rounded-xl border border-gray-200">
                        </div>

                        <h2 id="modal-title" class="text-m font-bold text-gray-900 mb-6"></h2>

                                                                                                                                                <!-- Special Template for Obituary -->
                                        <div id="obituary-template" class="hidden mt-6">
                                            <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">

                                                                                                                                                <!-- Vefat Bilgileri - Kompakt Tasarım -->
                                                <div id="obituary-details" class="mb-3">
                                                    <div class="bg-gradient-to-br from-slate-50 to-blue-50 rounded-lg p-3 border border-slate-200">
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <!-- Vefat Eden -->
                                            <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-slate-100">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600 text-sm"></i>
                                                                        </div>
                                                <div>
                                                    <div class="text-xs text-slate-500 font-medium">VEFAT EDEN</div>
                                                    <div id="obituary-deceased" class="text-sm font-semibold text-slate-800"></div>
                                                                </div>
                                                            </div>

                                            <!-- Vefat Tarihi -->
                                            <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-slate-100">
                                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-calendar text-red-600 text-sm"></i>
                                                                        </div>
                                                <div>
                                                    <div class="text-xs text-slate-500 font-medium">VEFAT TARİHİ</div>
                                                    <div id="obituary-death-date" class="text-sm font-semibold text-slate-800"></div>
                                                                </div>
                                                            </div>

                                            <!-- Cenaze Namazı -->
                                            <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-slate-100">
                                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-clock text-green-600 text-sm"></i>
                                                                        </div>
                                                <div>
                                                    <div class="text-xs text-slate-500 font-medium">CENAZE NAMAZI</div>
                                                    <div id="obituary-prayer-time" class="text-sm font-semibold text-slate-800"></div>
                                                                </div>
                                                            </div>

                                            <!-- Namaz Yeri -->
                                            <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-slate-100">
                                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-mosque text-purple-600 text-sm"></i>
                                                                        </div>
                                                <div>
                                                    <div class="text-xs text-slate-500 font-medium">NAMAZ YERİ</div>
                                                    <div id="obituary-prayer-location" class="text-sm font-semibold text-slate-800"></div>
                                                            </div>
                                                        </div>

                                            <!-- Defin Yeri -->
                                            <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-slate-100 md:col-span-2">
                                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-map-marker-alt text-orange-600 text-sm"></i>
                                                                    </div>
                                                <div>
                                                    <div class="text-xs text-slate-500 font-medium">DEFİN YERİ</div>
                                                    <div id="obituary-burial-location" class="text-sm font-semibold text-slate-800"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                <!-- Başsağlığı Mesajı -->
                                <div class="text-center py-4">
                                    <p class="text-slate-600 text-sm leading-relaxed">
                                        Vefat eden kardeşimize Allah'tan rahmet, yakınlarına başsağlığı dileriz.
                                    </p>
                                                </div>

                                <!-- Yönetim Kurulu İmzası -->
                                <div class="text-center b pt-4 border-t border-slate-200">
                                    <div class="text-slate-500 text-xs mb-1">Yönetim Kurulu Adına</div>
                                    <div class="text-slate-700 text-sm font-semibold">{{ \App\Models\Settings::get('pdf_president_name', 'Dernek Başkanı') }}</div>
                                    <div class="text-slate-500 text-xs">Dernek Başkanı</div>
                                </div>
                                                </div>
                                            </div>

                        <!-- Regular Content -->
                        <div id="regular-content" class="prose prose-lg max-w-none">
                            <div class="flex items-center text-sm text-slate-500 mb-6">
                                <span id="modal-date"></span>
                            </div>
                            <div id="modal-content-text">
                                <!-- Content will be inserted here -->
                                        </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <button onclick="closeAnnouncementModal()" class="modern-btn px-8 py-3 rounded-xl font-semibold">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentAnnouncementId = null;
        let currentAnnouncementTitle = null;

        // Get current announcement data for sharing
        function getCurrentAnnouncementData() {
            if (!currentAnnouncementId) return null;

            const detailElement = document.querySelector(`#announcement-detail-${currentAnnouncementId} .announcement-full-data`);
            if (!detailElement) return null;

            const url = window.location.origin + '/duyurular#' + currentAnnouncementId;
            const title = detailElement.dataset.title;
            const type = detailElement.dataset.type;

            let shareText = `"${title}"\n\n`;

            if (type === 'obituary') {
                const obituaryName = detailElement.dataset.obituaryName || '';
                const obituaryDate = detailElement.dataset.obituaryDate || '';
                const funeralTime = detailElement.dataset.funeralTime || '';
                const funeralPlace = detailElement.dataset.funeralPlace || '';

                shareText = `Vefat Duyurusu\n\n`;
                if (obituaryName) shareText += `${obituaryName} Vefat Etmiştir.\n\n`;

                if (obituaryName) shareText += `Vefat Eden: ${obituaryName}\n`;
                if (obituaryDate) shareText += `Vefat Tarihi: ${obituaryDate}\n`;
                if (funeralTime) shareText += `Cenaze Namazı: ${funeralTime}\n`;
                if (funeralPlace) shareText += `Namaz Yeri: ${funeralPlace}\n\n`;

                shareText += `Vefat eden kardeşimize Allah'tan rahmet, yakınlarına başsağlığı dileriz\n\n`;
                shareText += `Yönetim Kurulu Adına\n`;
                shareText += `${detailElement.dataset.presidentName || 'Dernek Başkanı'}\n`;
                shareText += `Dernek Başkanı\n\n`;
            }

            shareText += `\nDetaylı bilgi için: ${url}`;

            return {
                url: url,
                title: title,
                text: shareText
            };
        }

        // Share to WhatsApp
        function shareToWhatsApp() {
            const data = getCurrentAnnouncementData();
            if (!data) return;

            const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(data.text)}`;
            window.open(whatsappUrl, '_blank');
            showToast('WhatsApp\'ta paylaşılıyor...', 'success');
        }

        // Share to Facebook
        function shareToFacebook() {
            const data = getCurrentAnnouncementData();
            if (!data) return;

            const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(data.url)}&quote=${encodeURIComponent(data.text)}`;
            window.open(facebookUrl, '_blank', 'width=600,height=400');
            showToast('Facebook\'ta paylaşılıyor...', 'success');
        }

        // Share to Twitter
        function shareToTwitter() {
            const data = getCurrentAnnouncementData();
            if (!data) return;

            const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(data.text)}&url=${encodeURIComponent(data.url)}`;
            window.open(twitterUrl, '_blank', 'width=600,height=400');
            showToast('Twitter\'da paylaşılıyor...', 'success');
        }

        // Share to Instagram
        function shareToInstagram() {
            const data = getCurrentAnnouncementData();
            if (!data) return;

            // Instagram doesn't have direct sharing API, so we'll copy the text to clipboard
            if (navigator.clipboard) {
                navigator.clipboard.writeText(data.text).then(() => {
                    showToast('Metin kopyalandı! Instagram\'da paylaşabilirsiniz.', 'success');
                }).catch(() => {
                    fallbackCopyTextToClipboard(data.text);
                });
            } else {
                fallbackCopyTextToClipboard(data.text);
            }
        }

        // Copy announcement link
        function copyAnnouncementLink() {
            const data = getCurrentAnnouncementData();
            if (!data) return;

            if (navigator.clipboard) {
                navigator.clipboard.writeText(data.url).then(() => {
                    showToast('Link kopyalandı!', 'success');
                }).catch(() => {
                    fallbackCopyTextToClipboard(data.url);
                });
            } else {
                fallbackCopyTextToClipboard(data.url);
            }
        }

        // Fallback copy function for older browsers
        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.setAttribute('charset', 'UTF-8');
            textArea.style.fontFamily = 'Arial, sans-serif';
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                document.execCommand('copy');
                showToast('Link kopyalandı!', 'success');
            } catch (err) {
                showToast('Link kopyalanamadı', 'error');
            }

            document.body.removeChild(textArea);
        }

        // Show Toast Notification
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full flex items-center space-x-2`;

            if (type === 'success') {
                toast.className += ' bg-green-500';
                toast.innerHTML = `<i class="fas fa-check-circle"></i><span>${message}</span>`;
            } else if (type === 'error') {
                toast.className += ' bg-red-500';
                toast.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${message}</span>`;
            } else {
                toast.className += ' bg-blue-500';
                toast.innerHTML = `<i class="fas fa-info-circle"></i><span>${message}</span>`;
            }

            document.body.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Animate out and remove
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // Show Announcement Detail Modal
        function showAnnouncementDetail(announcementId) {
            const detailElement = document.querySelector(`#announcement-detail-${announcementId} .announcement-full-data`);

            if (!detailElement) {
                console.error('Announcement detail not found for ID:', announcementId);
                return;
            }

            const modal = document.getElementById('announcement-modal');
            const title = document.getElementById('modal-title');
            const date = document.getElementById('modal-date');
            const content = document.getElementById('modal-content-text');
            const typeBadge = document.getElementById('modal-type-badge');
            const typeText = document.getElementById('modal-type-text');
            const obituaryTemplate = document.getElementById('obituary-template');
            const regularContent = document.getElementById('regular-content');
            const featuredBadge = document.getElementById('modal-featured-badge');
            const coverImageContainer = document.getElementById('modal-cover-image');
            const coverImage = document.getElementById('modal-cover-img');

            // Set current announcement for sharing
            currentAnnouncementId = announcementId;
            currentAnnouncementTitle = detailElement.dataset.title;

            // Set content
            title.textContent = detailElement.dataset.title;
            date.textContent = detailElement.dataset.date;
            content.innerHTML = detailElement.dataset.content;
            typeText.textContent = detailElement.dataset.typeDisplay;

            // Set cover image
            const imageUrl = detailElement.dataset.imageUrl;
            if (imageUrl) {
                coverImage.src = imageUrl;
                coverImage.alt = detailElement.dataset.title;
                coverImageContainer.classList.remove('hidden');
            } else {
                coverImageContainer.classList.add('hidden');
            }

            // Set obituary details if available
            const obituaryName = detailElement.dataset.obituaryName;
            const obituaryDate = detailElement.dataset.obituaryDate;
            const funeralTime = detailElement.dataset.funeralTime;
            const funeralPlace = detailElement.dataset.funeralPlace;
            const burialPlace = detailElement.dataset.burialPlace;

            // Set type badge styling
            const type = detailElement.dataset.type;
            typeBadge.className = 'inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold mr-4 shadow-sm ';

            if (type === 'obituary') {
                    typeBadge.className += 'bg-gradient-to-r from-slate-600 to-gray-700 text-white';
                    obituaryTemplate.classList.remove('hidden');
                regularContent.classList.add('hidden');

                // Set obituary detail values
                if (obituaryName) document.getElementById('obituary-deceased').textContent = obituaryName;
                if (obituaryDate) document.getElementById('obituary-death-date').textContent = obituaryDate;
                if (funeralTime) document.getElementById('obituary-prayer-time').textContent = funeralTime;
                if (funeralPlace) document.getElementById('obituary-prayer-location').textContent = funeralPlace;
                if (burialPlace) document.getElementById('obituary-burial-location').textContent = burialPlace;
            } else {
                    typeBadge.className += 'bg-gradient-to-r from-blue-500 to-blue-600 text-white';
                    obituaryTemplate.classList.add('hidden');
                regularContent.classList.remove('hidden');
            }

            // Show/hide featured badge
            if (detailElement.dataset.featured === '1') {
                featuredBadge.classList.remove('hidden');
            } else {
                featuredBadge.classList.add('hidden');
            }

            // Show modal with animation
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Add entrance animation
            const modalContent = document.getElementById('modal-content');
            modalContent.classList.add('modal-enter');
        }

        // Close Announcement Detail Modal
        function closeAnnouncementModal() {
            const modal = document.getElementById('announcement-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentAnnouncementId = null;
            currentAnnouncementTitle = null;
        }

        // Close modal when clicking outside
        document.getElementById('announcement-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAnnouncementModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAnnouncementModal();
            }
        });
    </script>

    @include('partials.footer')
</body>
</html>
