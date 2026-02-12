<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.news') }} - {{ $orgName }}</title>

    <!-- Open Graph Meta Tags for Social Media Sharing -->
    <meta property="og:title" content="{{ __('common.news') }} - {{ $orgName }}">
    <meta property="og:description" content="{{ $orgName }} {{ __('common.news') }}. {{ __('common.all_news') }}.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $orgName }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ __('common.news') }} - {{ $orgName }}">
    <meta name="twitter:description" content="{{ $orgName }} {{ __('common.news') }}. {{ __('common.all_news') }}.">

    <!-- Additional Meta Tags -->
    <meta name="description" content="{{ $orgName }} {{ __('common.news') }}. {{ __('common.all_news') }}.">
    <meta name="keywords" content="{{ __('common.news') }}, {{ $orgName }}, cami, üyelik, {{ __('common.announcements') }}">
    <meta name="author" content="{{ $orgName }}">
    <meta name="robots" content="index, follow">
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

        .news-card {
            transition: all 0.3s ease;
        }

        .news-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
        
        /* Theme color classes */
        .theme-text { color: var(--theme-link-color) !important; }
        .theme-text-hover { color: var(--theme-hover-color) !important; }
        .theme-bg { background-color: var(--theme-primary-color) !important; }
        .theme-gradient-bg { background: var(--theme-gradient) !important; }
        .theme-border { border-color: var(--theme-link-color) !important; }

        .pagination-btn:hover {
            background: #0d9488;
            color: white;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
                    <nav class="flex items-center" aria-label="Breadcrumb">
                        <a href="/" class="text-sm font-medium text-gray-600 hover:text-teal-700 transition-colors duration-200">
                            {{ __('common.breadcrumb_home') }}
                        </a>
                        <span class="breadcrumb-separator">/</span>
                        <span class="text-sm font-semibold text-teal-800">{{ __('common.breadcrumb_news') }}</span>
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

                <!-- Right Content - Haberler -->
                <div class="lg:col-span-3">
                    <div class="elegant-card rounded-xl p-8">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-teal-800 mb-4">{{ __('common.news') }}</h1>
                            <div class="elegant-divider"></div>
                        </div>

                        @if($news->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($news as $item)
                                    <article class="news-card bg-white rounded-lg shadow-md overflow-hidden">
                                        <a href="{{ route('news.detail', $item->id) }}" class="block">
                                            @if($item->image_path)
                                                <div class="relative h-48 overflow-hidden">
                                                    <img src="{{ asset($item->image_path) }}" alt="{!! htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8') !!}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                                                </div>
                                            @endif

                                            <div class="p-4">
                                                <!-- Title -->
                                                <h3 class="text-lg font-bold text-gray-900 leading-tight line-clamp-2">
                                                    {!! htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8') !!}
                                                </h3>
                                            </div>
                                        </a>
                                    </article>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            @if($news->hasPages())
                                <div class="mt-12 flex justify-center">
                                    <div class="flex items-center space-x-2">
                                        @if($news->currentPage() > 1)
                                            <a href="{{ $news->previousPageUrl() }}" class="pagination-btn w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                                                <i class="fas fa-chevron-left text-sm"></i>
                                            </a>
                                        @endif

                                        @for($i = 1; $i <= $news->lastPage(); $i++)
                                            @if($i <= 3 || $i > $news->lastPage() - 3 || abs($i - $news->currentPage()) <= 1)
                                                <a href="{{ $news->url($i) }}" class="pagination-btn w-10 h-10 rounded-lg {{ $news->currentPage() == $i ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center font-medium">
                                                    {{ $i }}
                                                </a>
                                            @elseif($i == 4 || $i == $news->lastPage() - 3)
                                                <span class="text-gray-400">...</span>
                                            @endif
                                        @endfor

                                        @if($news->currentPage() < $news->lastPage())
                                            <a href="{{ $news->nextPageUrl() }}" class="pagination-btn w-10 h-10 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center">
                                                <i class="fas fa-chevron-right text-sm"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('common.no_news') }}</h3>
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

    @include('partials.footer')
</body>
</html>


