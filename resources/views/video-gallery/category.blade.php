<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $videoCategory->name }} - Video Galeri - {{ $orgName }}</title>
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

        .video-card {
            transition: all 0.3s ease;
        }

        .video-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .video-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .video-modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-container {
            position: relative;
            width: 90%;
            max-width: 1200px;
            aspect-ratio: 16/9;
            background: #000;
            border-radius: 12px;
            overflow: hidden;
        }

        .video-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .close-btn {
            position: absolute;
            top: -50px;
            right: 0;
            color: white;
            font-size: 24px;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
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
                            {{ __('common.home') }}
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <a href="{{ route('video-gallery.index') }}" class="text-sm font-medium text-gray-600 hover:text-teal-700 transition-colors duration-200">
                            {{ __('common.video_gallery') }}
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="text-sm font-semibold text-teal-800">{{ $videoCategory->name }}</span>
                    </nav>

                    <!-- Back Button -->
                    <a href="{{ route('video-gallery.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2 text-sm"></i>
                        {{ __('common.back_to_video_gallery') }}
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
                                {{ __('common.online_membership_form') }}
                            </a>

                            <a href="/uye-giris" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.login_to_member_panel') }}
                            </a>

                            <a href="/iletisim" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.contact') }}
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Right Content - Video Kategorisi -->
                <div class="lg:col-span-3">
                    <div class="elegant-card rounded-xl p-8">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-teal-800 mb-4">
                                <i class="fas fa-video mr-3"></i>
                                {{ $videoCategory->name }}
                            </h1>
                            @if($videoCategory->description)
                                <p class="text-gray-600 mb-4">{{ $videoCategory->description }}</p>
                            @endif
                            <div class="elegant-divider"></div>
                        </div>

                        <!-- Videos -->
                        @if($videos->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($videos as $index => $video)
                                    <div class="video-card bg-white rounded-xl shadow-md overflow-hidden cursor-pointer"
                                         onclick="openVideoModal('{{ $video->embed_url }}', '{{ addslashes($video->title) }}')">
                                        <!-- Video Thumbnail -->
                                        <div class="relative aspect-video overflow-hidden bg-gray-900 group">
                                            <img src="{{ $video->thumbnail_url }}"
                                                 alt="{{ $video->title }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                            <!-- Play Overlay -->
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/30 transition-colors duration-300">
                                                <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                    <i class="fas fa-play text-white text-xl ml-1"></i>
                                                </div>
                                            </div>

                                            <!-- Duration Badge (if available) -->
                                            @if($video->duration)
                                                <div class="absolute bottom-3 right-3">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-black/70 text-white">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $video->duration }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Video Info -->
                                        <div class="p-6">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $video->title }}</h3>
                                            @if($video->description)
                                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $video->description }}</p>
                                            @endif

                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ $video->created_at->format('d.m.Y') }}
                                                </span>
                                                <span class="inline-flex items-center text-teal-600 hover:text-teal-700 font-medium text-sm transition-colors">
                                                    {{ __('common.watch') }}
                                                    <i class="fas fa-play ml-1"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-video text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('common.no_videos_found') }}</h3>
                                <p class="text-gray-600 mb-8 leading-relaxed">{{ __('common.no_videos_found_desc') }}</p>
                                <a href="{{ route('video-gallery.index') }}" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>{{ __('common.back_to_video_gallery') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('partials.footer')

    <!-- Video Modal -->
    <div id="videoModal" class="video-modal">
        <div class="video-container">
            <div class="close-btn" onclick="closeVideoModal()">
                <i class="fas fa-times"></i>
            </div>
            <iframe id="videoIframe" src="" allowfullscreen></iframe>
        </div>
    </div>

    <script>
        // Video Modal Functions
        function openVideoModal(embedUrl, title) {
            const modal = document.getElementById('videoModal');
            const iframe = document.getElementById('videoIframe');

            iframe.src = embedUrl;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeVideoModal() {
            const modal = document.getElementById('videoModal');
            const iframe = document.getElementById('videoIframe');

            modal.classList.remove('active');
            iframe.src = '';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('videoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeVideoModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeVideoModal();
            }
        });
    </script>
</body>
</html>
