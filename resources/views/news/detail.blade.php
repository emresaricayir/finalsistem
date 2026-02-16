<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }} - {{ $orgName }}</title>

    <!-- Open Graph Meta Tags for Social Media Sharing -->
    <meta property="og:title" content="{{ $news->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($news->content), 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $orgName }}">
    @if($news->image_path)
        <meta property="og:image" content="{{ asset($news->image_path) }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt" content="{{ $news->title }}">
    @endif

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $news->title }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($news->content), 160) }}">
    @if($news->image_path)
        <meta name="twitter:image" content="{{ asset($news->image_path) }}">
        <meta name="twitter:image:alt" content="{{ $news->title }}">
    @endif

    <!-- Additional Meta Tags -->
    <meta name="description" content="{{ Str::limit(strip_tags($news->content), 160) }}">
    <meta name="keywords" content="haber, {{ $orgName }}, cami, üyelik">
    <meta name="author" content="{{ $orgName }}">
    <meta name="robots" content="index, follow">

    <!-- Article Specific Meta Tags -->
    <meta property="article:published_time" content="{{ $news->created_at->toISOString() }}">
    <meta property="article:modified_time" content="{{ $news->updated_at->toISOString() }}">
    <meta property="article:author" content="{{ $orgName }}">
    <meta property="article:section" content="{{ __('common.news') }}">
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
        
        /* Theme color classes */
        .theme-text { color: var(--theme-link-color) !important; }
        .theme-text-hover { color: var(--theme-hover-color) !important; }
        .theme-bg { background-color: var(--theme-primary-color) !important; }
        .theme-gradient-bg { background: var(--theme-gradient) !important; }
        .theme-border { border-color: var(--theme-link-color) !important; }

        .share-btn {
            transition: all 0.2s ease;
        }

        .share-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .prose {
            color: #374151;
            line-height: 1.7;
        }

        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            color: #111827;
            font-weight: 600;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .prose p {
            margin-bottom: 1.5rem;
        }

        .prose img {
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

    </style>
</head>
<body class="min-h-screen">

    @include('partials.header-menu-wrapper')

    <!-- Main Container -->
    <div class="relative min-h-screen">

        <!-- Top Navigation Bar -->
        <div class="relative elegant-card border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <!-- Breadcrumb -->
                    <nav class="flex items-center flex-wrap" aria-label="Breadcrumb">
                        <a href="/" class="text-xs sm:text-sm font-medium text-gray-600 transition-colors duration-200" style="--hover-color: var(--theme-link-color, #0d9488);" onmouseover="this.style.color='var(--theme-link-color, #0d9488)'" onmouseout="this.style.color='#4b5563'">
                            {{ __('common.breadcrumb_home') }}
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <a href="{{ route('news.all') }}" class="text-xs sm:text-sm font-medium text-gray-600 transition-colors duration-200" style="--hover-color: var(--theme-link-color, #0d9488);" onmouseover="this.style.color='var(--theme-link-color, #0d9488)'" onmouseout="this.style.color='#4b5563'">
                            {{ __('common.breadcrumb_news') }}
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="text-xs sm:text-sm font-semibold hidden sm:inline" style="color: var(--theme-hover-color, #0f766e);">{{ Str::limit($news->title, 30) }}</span>
                        <span class="text-xs sm:text-sm font-semibold sm:hidden" style="color: var(--theme-hover-color, #0f766e);">{{ Str::limit($news->title, 15) }}</span>
                    </nav>

                    <!-- Back Button -->
                    <a href="{{ route('news.all') }}" class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 text-sm">
                        <i class="fas fa-arrow-left mr-2 text-sm"></i>
                        <span class="hidden sm:inline">{{ __('common.back_to', ['item' => __('common.news')]) }}</span>
                        <span class="sm:hidden">{{ __('common.back') }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">

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

                            <a href="/haberler" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200 bg-teal-50 text-teal-700">
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

                <!-- Right Content - Haber Detayı -->
                <div class="lg:col-span-3">
                    <article class="elegant-card rounded-xl overflow-hidden">
                        <!-- Featured Image -->
                        @if($news->image_path)
                            <div class="w-full h-64 sm:h-80 lg:h-96 relative">
                                <img src="{{ asset($news->image_path) }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute top-3 left-3 sm:top-4 sm:left-4">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-1 sm:space-y-0 sm:space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium bg-white/90 text-blue-800 backdrop-blur-sm">
                                            <i class="fas fa-newspaper mr-1 sm:mr-2"></i>{{ __('common.news') }}
                                        </span>
                                        @if($news->is_featured)
                                            <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium bg-white/90 text-purple-800 backdrop-blur-sm">
                                                <i class="fas fa-star mr-1 sm:mr-2"></i>{{ __('common.featured') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="p-4 sm:p-6 lg:p-8">
                            <!-- Header -->
                            <div class="mb-6 sm:mb-8">
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-4 leading-tight">{!! htmlspecialchars($news->title, ENT_QUOTES, 'UTF-8') !!}</h1>

                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                                    <div class="flex items-center text-xs sm:text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>{{ $news->published_at ? $news->published_at->formatTr('d F Y, H:i') : $news->created_at->formatTr('d F Y, H:i') }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-eye mr-1"></i>
                                        <span>{{ rand(50, 200) }} {{ __('common.read_count') }}</span>
                                    </div>

                                    <!-- Share Button -->
                                    <div class="flex items-center">
                                        <button onclick="shareNews()" class="share-btn px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center gap-2 text-sm font-medium transition-colors" title="Paylaş">
                                            <i class="fas fa-share-alt"></i>
                                            <span>{{ __('common.share') }}</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="elegant-divider"></div>
                            </div>

                            <!-- Content -->
                            <div class="prose prose-sm sm:prose-base lg:prose-lg max-w-none">
                                @if($news->content)
                                    {!! $news->content !!}
                                @else
                                    <p class="text-gray-600">{{ __('common.no_content') }}</p>
                                @endif
                            </div>

                            @if($news->photos && $news->photos->count())
                                <div class="mt-8 sm:mt-10">
                                    <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Foto Galeri</h2>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-4">
                                        @foreach($news->photos as $index => $photo)
                                            <button type="button" class="group relative overflow-hidden rounded-lg border focus:outline-none" data-full="{{ asset($photo->image_path) }}" data-index="{{ $index }}" onclick="openLightbox(this, {{ $index }})">
                                                <img src="{{ asset($photo->image_path) }}" alt="{{ $photo->caption }}" class="w-full h-32 sm:h-40 object-cover group-hover:scale-105 transition-transform duration-300">
                                                @if($photo->caption)
                                                    <div class="absolute bottom-0 inset-x-0 bg-black/50 text-white text-xs px-2 py-1">{{ $photo->caption }}</div>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </div>
                    </article>

                    <!-- Related News or Navigation -->
                    <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-0">
                        <a href="{{ route('news.all') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors duration-200 text-sm sm:text-base">
                            <i class="fas fa-arrow-left mr-2"></i>
                            {{ __('common.all_news') }}
                        </a>
                        <a href="/" class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200 text-sm sm:text-base">
                            <i class="fas fa-home mr-2"></i>
                            {{ __('common.home') }}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="fixed inset-0 bg-black/90 hidden items-center justify-center z-50">
        <!-- Close Button -->
        <button type="button" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 transition-colors z-10 bg-black/50 rounded-full w-10 h-10 flex items-center justify-center" onclick="closeLightbox()" title="Kapat (ESC)">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Previous Button -->
        <button type="button" id="lightbox-prev" class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-2xl hover:text-gray-300 transition-colors z-10 bg-black/50 rounded-full w-12 h-12 flex items-center justify-center hidden" onclick="changeImage(-1)" title="Önceki (←)">
            <i class="fas fa-chevron-left"></i>
        </button>
        
        <!-- Next Button -->
        <button type="button" id="lightbox-next" class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-2xl hover:text-gray-300 transition-colors z-10 bg-black/50 rounded-full w-12 h-12 flex items-center justify-center hidden" onclick="changeImage(1)" title="Sonraki (→)">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Image Container -->
        <div class="relative max-h-[90vh] max-w-[90vw] flex items-center justify-center">
            <img id="lightbox-img" src="" alt="" class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-2xl select-none" />
        </div>
        
        <!-- Image Counter -->
        <div id="lightbox-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white bg-black/50 px-4 py-2 rounded-full text-sm hidden">
            <span id="current-index">1</span> / <span id="total-count">1</span>
        </div>
        
        <!-- Image Caption -->
        <div id="lightbox-caption" class="absolute bottom-16 left-1/2 -translate-x-1/2 text-white bg-black/50 px-4 py-2 rounded-lg text-sm max-w-md text-center hidden"></div>
    </div>
    
    <!-- Store gallery images data -->
    <script>
        window.galleryImages = [
            @if($news->photos && $news->photos->count())
                @foreach($news->photos as $photo)
                    {
                        src: '{{ asset($photo->image_path) }}',
                        caption: {!! json_encode($photo->caption ?? '', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}
                    }{{ !$loop->last ? ',' : '' }}
                @endforeach
            @endif
        ];
        window.currentImageIndex = 0;
    </script>

    <script>
        // Wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            function shareNews() {
                // PHP tarafında HTML entity'leri decode et
                @php
                    $cleanTitle = html_entity_decode($news->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $cleanDescription = html_entity_decode(Str::limit(strip_tags($news->content), 100), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                @endphp
                
                const title = {!! json_encode($cleanTitle, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!};
                const description = {!! json_encode($cleanDescription, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!};
                const url = window.location.href;

                // Native Share API kullan (tüm paylaşım seçeneklerini gösterir)
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        text: description,
                        url: url
                    }).then(() => {
                        // Paylaşım başarılı
                    }).catch((error) => {
                        // Kullanıcı paylaşımı iptal etti veya hata oluştu
                        if (error.name !== 'AbortError') {
                            console.error('Paylaşım hatası:', error);
                            // Fallback: Linki kopyala
                            copyLink();
                        }
                    });
                } else {
                    // Native Share API desteklenmiyorsa linki kopyala
                    copyLink();
                }
            }

            function copyLink() {
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    showToast({!! json_encode(__('common.link_copied'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}, 'success');
                }).catch(() => {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.setAttribute('charset', 'UTF-8');
                    textArea.style.fontFamily = 'Arial, sans-serif';
                    textArea.value = url;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    showToast({!! json_encode(__('common.link_copied'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}, 'success');
                });
            }

            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full`;

                if (type === 'success') {
                    toast.className += ' bg-green-500';
                } else {
                    toast.className += ' bg-blue-500';
                }

                toast.textContent = message;
                document.body.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Animate out and remove
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 300);
                }, 3000);
            }

            // Lightbox functions - make them globally available
            window.openLightbox = function(el, index){
                if (!window.galleryImages || window.galleryImages.length === 0) return;
                
                window.currentImageIndex = index || 0;
                const lb = document.getElementById('lightbox');
                const img = document.getElementById('lightbox-img');
                const prevBtn = document.getElementById('lightbox-prev');
                const nextBtn = document.getElementById('lightbox-next');
                const counter = document.getElementById('lightbox-counter');
                const caption = document.getElementById('lightbox-caption');
                
                if (lb && img) {
                    updateLightboxImage();
                    lb.classList.remove('hidden');
                    lb.classList.add('flex');
                    document.body.style.overflow = 'hidden'; // Prevent background scrolling
                    
                    // Show/hide navigation buttons
                    if (window.galleryImages.length > 1) {
                        prevBtn.classList.remove('hidden');
                        nextBtn.classList.remove('hidden');
                        counter.classList.remove('hidden');
                        updateCounter();
                    } else {
                        prevBtn.classList.add('hidden');
                        nextBtn.classList.add('hidden');
                        counter.classList.add('hidden');
                    }
                }
            };
            
            function updateLightboxImage() {
                if (!window.galleryImages || window.galleryImages.length === 0) return;
                
                const img = document.getElementById('lightbox-img');
                const caption = document.getElementById('lightbox-caption');
                
                if (img) {
                    const currentImage = window.galleryImages[window.currentImageIndex];
                    img.src = currentImage.src;
                    img.alt = currentImage.caption || '';
                    
                    // Update caption
                    if (caption) {
                        if (currentImage.caption) {
                            caption.textContent = currentImage.caption;
                            caption.classList.remove('hidden');
                        } else {
                            caption.classList.add('hidden');
                        }
                    }
                    
                    updateCounter();
                }
            }
            
            function updateCounter() {
                const currentIndexEl = document.getElementById('current-index');
                const totalCountEl = document.getElementById('total-count');
                
                if (currentIndexEl) {
                    currentIndexEl.textContent = window.currentImageIndex + 1;
                }
                if (totalCountEl) {
                    totalCountEl.textContent = window.galleryImages.length;
                }
            }
            
            window.changeImage = function(direction) {
                if (!window.galleryImages || window.galleryImages.length === 0) return;
                
                window.currentImageIndex += direction;
                
                // Loop around
                if (window.currentImageIndex < 0) {
                    window.currentImageIndex = window.galleryImages.length - 1;
                } else if (window.currentImageIndex >= window.galleryImages.length) {
                    window.currentImageIndex = 0;
                }
                
                updateLightboxImage();
            };
            
            window.closeLightbox = function(){
                const lb = document.getElementById('lightbox');
                if (lb) {
                    lb.classList.add('hidden');
                    lb.classList.remove('flex');
                    document.body.style.overflow = 'auto'; // Restore scrolling
                }
            };
            
            // Set up event listeners
            const lightbox = document.getElementById('lightbox');
            if (lightbox) {
                lightbox.addEventListener('click', function(e){ 
                    // Close if clicking on background (not on image or buttons)
                    if(e.target === this || (e.target.id === 'lightbox-img' && e.target === e.currentTarget.querySelector('#lightbox-img'))){ 
                        closeLightbox(); 
                    } 
                });
            }
            
            // Keyboard navigation
            document.addEventListener('keydown', function(e){ 
                const lb = document.getElementById('lightbox');
                if (!lb || lb.classList.contains('hidden')) return;
                
                if(e.key === 'Escape'){ 
                    closeLightbox(); 
                } else if(e.key === 'ArrowLeft') {
                    e.preventDefault();
                    changeImage(-1);
                } else if(e.key === 'ArrowRight') {
                    e.preventDefault();
                    changeImage(1);
                }
            });
            
            // Touch/Swipe support for mobile
            let touchStartX = 0;
            let touchEndX = 0;
            
            if (lightbox) {
                lightbox.addEventListener('touchstart', function(e) {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });
                
                lightbox.addEventListener('touchend', function(e) {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                }, { passive: true });
            }
            
            function handleSwipe() {
                const swipeThreshold = 50; // Minimum swipe distance
                const diff = touchStartX - touchEndX;
                
                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        // Swipe left - next image
                        changeImage(1);
                    } else {
                        // Swipe right - previous image
                        changeImage(-1);
                    }
                }
            }

            // Make shareNews globally available for onclick handlers
            window.shareNews = shareNews;
        });
    </script>

    @include('partials.footer')
</body>
</html>
