<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - {{ $orgName }}</title>
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

        .gallery-card {
            transition: all 0.3s ease;
        }

        .gallery-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
                        <a href="/" class="text-sm font-medium text-gray-600 transition-colors duration-200" style="--hover-color: var(--theme-link-color, #0d9488);" onmouseover="this.style.color='var(--theme-link-color, #0d9488)'" onmouseout="this.style.color='#4b5563'">
                            {{ __('common.home') }}
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="text-sm font-semibold" style="color: var(--theme-hover-color, #0f766e);">{{ __('common.gallery') }}</span>
                    </nav>

                    <!-- Back Button -->
                    <a href="javascript:history.back()" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2 text-sm"></i>
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

                <!-- Right Content - Galeri -->
                <div class="lg:col-span-3">
                    <div class="elegant-card rounded-xl p-8">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold mb-4" style="color: var(--theme-hover-color, #0f766e);">{{ __('common.gallery') }}</h1>
                            <div class="elegant-divider"></div>
                        </div>

                        <!-- Gallery Categories -->
                        @if($categories->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($categories as $index => $category)
                                    <div class="gallery-card bg-white rounded-xl shadow-md overflow-hidden">
                                        <!-- Category Cover -->
                                        <div class="relative h-48 overflow-hidden">
                                            @if($category->cover_image)
                                                <img src="{{ asset('storage/' . $category->cover_image) }}"
                                                     alt="{{ $category->name }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-teal-400 to-cyan-500 flex items-center justify-center">
                                                    <i class="fas fa-images text-4xl text-white/80"></i>
                                                </div>
                                            @endif
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                                            <!-- Image Count Badge -->
                                            <div class="absolute top-3 right-3">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/90 text-gray-700">
                                                    <i class="fas fa-camera mr-1"></i>
                                                    {{ $category->active_images_count }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Category Info -->
                                        <div class="p-6">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $category->name }}</h3>
                                            @if($category->description)
                                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $category->description }}</p>
                                            @endif

                                            <a href="{{ route('gallery.category', $category->slug) }}"
                                               class="inline-flex items-center text-teal-600 hover:text-teal-700 font-medium transition-colors">
                                                {{ __('common.go_to_gallery') }}
                                                <i class="fas fa-arrow-right ml-2"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-images text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('common.no_gallery_categories') }}</h3>
                                <p class="text-gray-600 mb-8 leading-relaxed">{{ __('common.no_gallery_categories_desc') }}</p>
                                <a href="/" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                    <i class="fas fa-home mr-2"></i>{{ __('common.back_to_home') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
