<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - Galeri - {{ $orgName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
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

        .gallery-image {
            transition: all 0.3s ease;
        }

        .gallery-image:hover {
            transform: scale(1.05);
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
                        <a href="{{ route('gallery.index') }}" class="text-sm font-medium text-gray-600 hover:text-teal-700 transition-colors duration-200">
                            {{ __('common.gallery') }}
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="text-sm font-semibold text-teal-800">{{ $category->name }}</span>
                    </nav>

                    <!-- Back Button -->
                    <a href="{{ route('gallery.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2 text-sm"></i>
                        {{ __('common.back_to_gallery') }}
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

                <!-- Right Content - Galeri Kategorisi -->
                <div class="lg:col-span-3">
                    <div class="elegant-card rounded-xl p-8">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-teal-800 mb-4">{{ $category->name }}</h1>
                            @if($category->description)
                                <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                            @endif
                            <div class="elegant-divider"></div>
                        </div>

                        <!-- Gallery Images -->
                        @if($images->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                @foreach($images as $index => $image)
                                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                                        <div class="relative aspect-w-4 aspect-h-3">
                                            <a href="{{ asset('storage/' . $image->image_path) }}"
                                               data-fancybox="gallery"
                                               data-caption="{{ $image->title }}{{ $image->description ? ' - ' . $image->description : '' }}">
                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                     alt="{{ $image->alt_text ?: $image->title }}"
                                                     class="w-full h-48 object-cover gallery-image">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                                    <i class="fas fa-search-plus text-white text-2xl opacity-0 hover:opacity-100 transition-opacity duration-300"></i>
                                                </div>
                                            </a>
                                        </div>

                                        @if($image->title || $image->description)
                                            <div class="p-4">
                                                @if($image->title)
                                                    <h3 class="text-sm font-semibold text-gray-900 mb-1">{{ $image->title }}</h3>
                                                @endif
                                                @if($image->description)
                                                    <p class="text-xs text-gray-600 line-clamp-2">{{ $image->description }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-images text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('common.no_photos_found') }}</h3>
                                <p class="text-gray-600 mb-8 leading-relaxed">{{ __('common.no_photos_found_desc') }}</p>
                                <a href="{{ route('gallery.index') }}" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>{{ __('common.back_to_gallery') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('partials.footer')

    <!-- Fancybox Script -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox]", {
            // Fancybox options
        });
    </script>
</body>
</html>
