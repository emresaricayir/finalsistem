<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->title }} - {{ $orgName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">


    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $announcement->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($announcement->content), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $orgName }}">
    @if($announcement->image_url)
        <meta property="og:image" content="{{ $announcement->image_url }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="{{ $announcement->title }}">
    @else
        <meta property="og:image" content="{{ asset('storage/logos/logo_1756715572.png') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="{{ $orgName }}">
    @endif

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $announcement->title }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($announcement->content), 160) }}">
    @if($announcement->image_url)
        <meta name="twitter:image" content="{{ $announcement->image_url }}">
        <meta name="twitter:image:alt" content="{{ $announcement->title }}">
    @else
        <meta name="twitter:image" content="{{ asset('storage/logos/logo_1756715572.png') }}">
        <meta name="twitter:image:alt" content="{{ $orgName }}">
    @endif

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
            background: var(--theme-gradient);
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
            box-shadow: 0 10px 20px rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.3);
            background: var(--theme-hover-color);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Theme color classes */
        .theme-text { color: var(--theme-link-color) !important; }
        .theme-text-hover { color: var(--theme-hover-color) !important; }
        .theme-bg { background-color: var(--theme-primary-color) !important; }
        .theme-gradient-bg { background: var(--theme-gradient) !important; }
        .theme-border { border-color: var(--theme-link-color) !important; }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .main-content {
                padding: 0.5rem;
            }

            .announcement-title {
                font-size: 1.25rem !important;
                line-height: 1.2;
                margin-bottom: 0.75rem;
            }

            .share-buttons {
                flex-wrap: wrap;
                gap: 0.25rem;
                margin-top: 0.5rem;
            }

            .share-btn {
                width: 2.25rem !important;
                height: 2.25rem !important;
            }

            .badge-container {
                flex-direction: column;
                gap: 0.25rem;
                margin-bottom: 1rem;
            }

            .badge {
                width: 100%;
                justify-content: center;
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }

            .obituary-grid {
                grid-template-columns: 1fr !important;
                gap: 0.5rem;
            }

            .obituary-item {
                padding: 0.5rem;
                font-size: 0.875rem;
            }

            .prose {
                font-size: 0.875rem;
                line-height: 1.5;
            }

            .breadcrumb {
                flex-wrap: wrap;
                gap: 0.125rem;
                font-size: 0.75rem;
            }

            .back-button {
                width: 2.5rem;
                height: 2.5rem;
                margin-top: 0.5rem;
                align-self: flex-end;
            }

            .elegant-card {
                padding: 1rem !important;
            }

            .header-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-section .share-buttons {
                align-self: flex-end;
                margin-top: 0.5rem;
            }
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
                    <nav class="flex items-center breadcrumb" aria-label="Breadcrumb">
                        <a href="/" class="text-sm font-medium text-gray-600 transition-colors duration-200" style="--hover-color: var(--theme-link-color, #0d9488);" onmouseover="this.style.color='var(--theme-link-color, #0d9488)'" onmouseout="this.style.color='#4b5563'">
                            {{ __('common.breadcrumb_home') }}
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <a href="/duyurular" class="text-sm font-medium text-gray-600 transition-colors duration-200" style="--hover-color: var(--theme-link-color, #0d9488);" onmouseover="this.style.color='var(--theme-link-color, #0d9488)'" onmouseout="this.style.color='#4b5563'">
                            {{ __('common.breadcrumb_announcements') }}
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="text-sm font-semibold" style="color: var(--theme-hover-color, #0f766e);">{{ Str::limit($announcement->title, 30) }}</span>
            </nav>

                    <!-- Back Button -->
                    <a href="/duyurular" class="inline-flex items-center justify-center w-8 h-8 rounded-full border transition-all duration-200 hover:shadow-md back-button" style="background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.1); border-color: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2); color: var(--theme-link-color, #0d9488);" onmouseover="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.2)'" onmouseout="this.style.background='rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.1)'" title="{{ __('common.back') }}">
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

                <!-- Right Content - Duyuru Detayı -->
                <div class="lg:col-span-3">
                    <div class="elegant-card rounded-xl p-8 fade-in">
                        <!-- Header with Share Buttons -->
                        <div class="mb-8">
                            <div class="flex items-start justify-between mb-4 header-section">
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight flex-1 pr-4 announcement-title">{{ $announcement->title }}</h1>

                                <!-- Social Media Share Buttons -->
                                <div class="flex gap-2 share-buttons">
                                    <button onclick="shareToWhatsApp()" class="flex items-center justify-center w-10 h-10 bg-green-500 hover:bg-green-600 text-white rounded-full transition-all duration-200 hover:scale-110 share-btn" title="WhatsApp'ta Paylaş">
                                        <i class="fab fa-whatsapp text-sm"></i>
                                    </button>
                                    <button onclick="shareToFacebook()" class="flex items-center justify-center w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full transition-all duration-200 hover:scale-110 share-btn" title="Facebook'ta Paylaş">
                                        <i class="fab fa-facebook-f text-sm"></i>
                                    </button>
                                    <button onclick="shareToInstagram()" class="flex items-center justify-center w-10 h-10 bg-pink-500 hover:bg-pink-600 text-white rounded-full transition-all duration-200 hover:scale-110 share-btn" title="Instagram'da Paylaş">
                                        <i class="fab fa-instagram text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mb-4 badge-container">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $announcement->type_badge_class }} badge">
                                    @if($announcement->type === 'obituary')
                                        <i class="fas fa-dove mr-2"></i>
                                    @else
                                        <i class="fas fa-bullhorn mr-2"></i>
                                    @endif
                                    {{ $announcement->type_display }}
                                </span>

                                @if($announcement->is_featured)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 badge">
                                        <i class="fas fa-star mr-2"></i>{{ __('common.featured') }}
                                    </span>
                                @endif

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 badge">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $announcement->created_at->format('d F Y, H:i') }}
                                </span>
                            </div>
                        </div>

                        <!-- Cover Image - Standard Size -->
                        @if($announcement->image_url)
                            <div class="mb-8 overflow-hidden rounded-xl border border-gray-200 shadow-lg cursor-pointer max-w-lg mx-auto" onclick="openImageModal('{{ $announcement->image_url }}', '{{ $announcement->title }}')">
                                <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" class="w-full h-auto object-contain bg-gray-50 hover:opacity-90 transition-opacity duration-200" style="max-height: 400px;">
                            </div>
                        @endif

                        <!-- Vefat Duyurusu Özel Bilgileri -->
                        @if($announcement->type === 'obituary' && ($announcement->obituary_name || $announcement->obituary_date || $announcement->funeral_time || $announcement->funeral_place || $announcement->burial_place))
                            <div class="mb-8">
                                <div class="bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                                    <!-- Vefat Bilgileri - Kompakt Tasarım -->
                                    <div class="mb-6">
                                        <div class="bg-gradient-to-br from-slate-50 to-blue-50 rounded-lg p-4 border border-slate-200">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 obituary-grid">
                                                <!-- Vefat Eden -->
                                                @if($announcement->obituary_name)
                                                    <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-slate-100 obituary-item">
                                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-user text-blue-600 text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-xs text-slate-500 font-medium">{{ __('common.deceased') }}</div>
                                                            <div class="text-sm font-semibold text-slate-800">{{ $announcement->obituary_name }}</div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Vefat Tarihi -->
                                                @if($announcement->obituary_date)
                                                    <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-slate-100">
                                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-calendar text-red-600 text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-xs text-slate-500 font-medium">{{ __('common.death_date') }}</div>
                                                            <div class="text-sm font-semibold text-slate-800">{{ $announcement->obituary_date->format('d.m.Y') }}</div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Cenaze Namazı -->
                                                @if($announcement->funeral_time)
                                                    <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-slate-100">
                                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-clock text-green-600 text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-xs text-slate-500 font-medium">{{ __('common.funeral_prayer') }}</div>
                                                            <div class="text-sm font-semibold text-slate-800">{{ $announcement->funeral_time->format('H:i') }}</div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Namaz Yeri -->
                                                @if($announcement->funeral_place)
                                                    <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-slate-100">
                                                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-mosque text-purple-600 text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-xs text-slate-500 font-medium">{{ __('common.prayer_place') }}</div>
                                                            <div class="text-sm font-semibold text-slate-800">{{ $announcement->funeral_place }}</div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Defin Yeri -->
                                                @if($announcement->burial_place)
                                                    <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border border-slate-100 md:col-span-2">
                                                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-map-marker-alt text-orange-600 text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <div class="text-xs text-slate-500 font-medium">{{ __('common.burial_place') }}</div>
                                                            <div class="text-sm font-semibold text-slate-800">{{ $announcement->burial_place }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Başsağlığı Mesajı -->
                                    <div class="text-center py-4">
                                        <p class="text-slate-600 text-sm leading-relaxed">
                                            {{ __('common.condolences') }}
                                        </p>
                                    </div>

                                    <!-- Yönetim Kurulu İmzası -->
                                    <div class="text-center pt-4 border-t border-slate-200">
                                        <div class="text-slate-500 text-xs mb-1">{{ __('common.on_behalf_of') }}</div>
                                        <div class="text-slate-700 text-sm font-semibold">{{ \App\Models\Settings::get('pdf_president_name', __('common.president')) }}</div>
                                        <div class="text-slate-500 text-xs">{{ __('common.president') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Content -->
                        @if($announcement->content)
                            <div class="prose prose-lg max-w-none mb-8">
                                {!! nl2br(e($announcement->content)) !!}
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden overflow-auto" onclick="closeImageModal()">
        <div class="min-h-full flex items-center justify-center p-4" onclick="event.stopPropagation()">
            <div class="relative max-w-7xl w-full">
                <button onclick="closeImageModal()" class="fixed top-4 right-4 text-white hover:text-gray-300 text-2xl font-bold z-10 bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
                <img id="modalImage" src="" alt="" class="w-full h-auto object-contain">
                <div id="modalTitle" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white p-4 text-center font-medium"></div>
            </div>
        </div>
    </div>

    <script>
        // Share functions - Global scope'a ekle
        window.shareToWhatsApp = function() {
            const url = window.location.href;
            const title = @json($announcement->title);
            const content = @json(Str::limit(strip_tags($announcement->content ?? ''), 200));
            const orgName = @json($orgName);
            const date = @json($announcement->created_at->format('d.m.Y'));

            let text = "*" + title + "*\n\n";

            @if($announcement->content && trim($announcement->content))
            text += content;
            @if(strlen(strip_tags($announcement->content)) > 200)
            text += "...";
            @endif
            text += "\n\n";
            @endif

            text += "Tarih: " + date + "\n";
            text += orgName + "\n\n";
            text += "Detaylı bilgi için: " + url;

            const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(whatsappUrl, '_blank');
        };

        window.shareToFacebook = function() {
            const url = window.location.href;
            const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            window.open(facebookUrl, '_blank', 'width=600,height=400');
        };

        window.shareToInstagram = function() {
            const url = window.location.href;
            const title = @json($announcement->title);
            const content = @json(Str::limit(strip_tags($announcement->content ?? ''), 100));

            let text = title + "\n\n";
            @if($announcement->content && trim($announcement->content))
            text += content;
            @if(strlen(strip_tags($announcement->content)) > 100)
            text += "...";
            @endif
            text += "\n\n";
            @endif
            text += url;

            // Instagram uygulamasına yönlendir (mobilde)
            if (/Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                // Mobil cihazda Instagram uygulamasına yönlendir
                const instagramUrl = `instagram://story-camera`;
                window.location.href = instagramUrl;

                // Fallback: Instagram web'e yönlendir
                setTimeout(() => {
                    window.open('https://www.instagram.com/', '_blank');
                }, 1000);
            } else {
                // Desktop'ta Instagram web'e yönlendir
                window.open('https://www.instagram.com/', '_blank');
            }

            // Metni de kopyala
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    window.showToast('Instagram açıldı, metin kopyalandı!', 'success');
                });
            } else {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                window.showToast('Instagram açıldı, metin kopyalandı!', 'success');
            }
        };

        window.copyLink = function() {
            const url = window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    window.showToast('Link kopyalandı!', 'success');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                window.showToast('Link kopyalandı!', 'success');
            }
        };

        window.showToast = function(message, type = 'info') {
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

        // Image Modal functions
        window.openImageModal = function(imageSrc, title) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');

            if (modal && modalImage && modalTitle) {
                modalImage.src = imageSrc;
                modalImage.alt = title;
                modalTitle.textContent = title;

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }
        };

        window.closeImageModal = function() {
            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Restore scrolling
            }
        };

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>

    @include('partials.footer')
</body>
</html>
