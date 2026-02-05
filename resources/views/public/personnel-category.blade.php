<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - {{ config('app.name') }}</title>
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
            background: var(--theme-link-color, #0d9488);
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

        .member-card {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .member-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
            border-color: var(--theme-link-color, #0d9488);
        }

        .circular-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            object-position: center;
            border: 4px solid white;
            box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .member-card:hover .circular-photo {
            transform: scale(1.05);
            box-shadow: 0 12px 35px -8px rgba(0, 0, 0, 0.3);
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

        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .contact-btn {
            transition: all 0.3s ease;
        }

        .contact-btn:hover {
            transform: scale(1.1);
        }

        .section-title {
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #0d9488, #14b8a6);
            border-radius: 2px;
        }

        .member-info {
            text-align: center;
            padding: 20px;
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

                            <a href="/iletisim" class="nav-item block px-4 py-3 text-gray-700 hover:text-teal-700 font-medium rounded-lg transition-all duration-200">
                                {{ __('common.contact') }}
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Right Content - Personnel -->
                <div class="lg:col-span-3">
                    <div class="elegant-card rounded-xl p-8">
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold mb-4" style="color: var(--theme-hover-color, #0f766e);">{{ $category->name }}</h1>

                            <div class="elegant-divider"></div>
                        </div>

                        @if($personnel->count() > 0)
                            @php
                                $leader = $personnel->where('sort_order', 0)->first();
                                $otherPersonnel = $personnel->where('sort_order', '>', 0);
                            @endphp

                            <!-- Leader Section (sort_order = 0) -->
                            @if($leader)
                                <div class="mb-12">

                                    <div class="flex justify-center">
                                        <div class="member-card bg-white rounded-xl shadow-md overflow-hidden w-80 fade-in">
                                            <!-- Member Photo -->
                                            <div class="flex justify-center pt-6 pb-2">
                                                @if($leader->image_path)
                                                    <img src="{{ asset('storage/' . $leader->image_path) }}"
                                                         alt="{{ $leader->name }}"
                                                         class="circular-photo">
                                                @else
                                                    <div class="circular-photo bg-gradient-to-br from-teal-100 to-cyan-100 flex items-center justify-center">
                                                        <span class="text-2xl text-teal-600">👤</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Member Info -->
                                            <div class="member-info">
                                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $leader->name }}</h3>
                                                <p class="text-teal-600 font-semibold mb-3 text-sm">{{ $leader->title }}</p>

                                                @if(!empty($leader->bio))
                                                    <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ Str::limit($leader->bio, 120) }}</p>
                                                @endif

                                                <!-- Contact & Social -->
                                                <div class="flex flex-wrap justify-center gap-2">
                                                    @if($leader->email)
                                                        <a href="mailto:{{ $leader->email }}" class="contact-btn w-8 h-8 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center hover:bg-teal-200" title="{{ __('common.email') }}">📧</a>
                                                    @endif
                                                    @if($leader->phone)
                                                        <a href="tel:{{ $leader->phone }}" class="contact-btn w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-200" title="{{ __('common.phone') }}">📞</a>
                                                    @endif
                                                    @if($leader->website)
                                                        <a href="{{ $leader->website }}" target="_blank" rel="noopener" class="contact-btn w-8 h-8 bg-gray-100 text-gray-700 rounded-full flex items-center justify-center hover:bg-gray-200" title="{{ __('common.web') }}">🌐</a>
                                                    @endif
                                                    @if($leader->linkedin)
                                                        <a href="{{ $leader->linkedin }}" target="_blank" rel="noopener" class="contact-btn w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-200" title="LinkedIn">in</a>
                                                    @endif
                                                    @if($leader->facebook)
                                                        <a href="{{ $leader->facebook }}" target="_blank" rel="noopener" class="contact-btn w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-200" title="Facebook">f</a>
                                                    @endif
                                                    @if($leader->twitter)
                                                        <a href="{{ $leader->twitter }}" target="_blank" rel="noopener" class="contact-btn w-8 h-8 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800" title="X">x</a>
                                                    @endif
                                                    @if($leader->instagram)
                                                        <a href="{{ $leader->instagram }}" target="_blank" rel="noopener" class="contact-btn w-8 h-8 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center hover:bg-pink-200" title="Instagram">◎</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Other Personnel Grid -->
                            @if($otherPersonnel->count() > 0)
                                <div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                        @foreach($otherPersonnel as $index => $member)
                                            <div class="member-card bg-white rounded-xl shadow-md overflow-hidden fade-in" style="animation-delay: {{ $index * 0.1 }}s;">
                                                <!-- Member Photo -->
                                                <div class="flex justify-center pt-6 pb-2">
                                                    @if($member->image_path)
                                                        <img src="{{ asset('storage/' . $member->image_path) }}"
                                                             alt="{{ $member->name }}"
                                                             class="circular-photo">
                                                    @else
                                                        <div class="circular-photo bg-gradient-to-br from-teal-100 to-cyan-100 flex items-center justify-center">
                                                            <span class="text-2xl text-teal-600">👤</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Member Info -->
                                                <div class="member-info">
                                                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $member->name }}</h3>
                                                    <p class="text-teal-600 font-semibold mb-3 text-sm">{{ $member->title }}</p>

                                                    @if(!empty($member->bio))
                                                        <p class="text-gray-600 text-sm leading-relaxed mb-4">{{ Str::limit($member->bio, 80) }}</p>
                                                    @endif

                                                    <!-- Contact & Social -->
                                                    <div class="flex flex-wrap justify-center gap-2">
                                                        @if($member->email)
                                                            <a href="mailto:{{ $member->email }}" class="contact-btn w-8 h-8 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center hover:bg-teal-200" title="{{ __('common.email') }}">📧</a>
                                                        @endif
                                                        @if($member->phone)
                                                            <a href="tel:{{ $member->phone }}" class="contact-btn w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-200" title="{{ __('common.phone') }}">📞</a>
                                                        @endif
                                                        @if($member->website)
                                                            <a href="{{ $member->website }}" target="_blank" rel="noopener" class="contact-btn w-8 h-8 bg-gray-100 text-gray-700 rounded-full flex items-center justify-center hover:bg-gray-200" title="{{ __('common.web') }}">🌐</a>
                                                        @endif
                                                        @if($member->linkedin)
                                                            <a href="{{ $member->linkedin }}" target="_blank" rel="noopener" class="contact-btn w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-200" title="LinkedIn">in</a>
                                                        @endif
                                                        @if($member->facebook)
                                                            <a href="{{ $member->facebook }}" target="_blank" rel="noopener" class="contact-btn w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-200" title="Facebook">f</a>
                                                        @endif
                                                        @if($member->twitter)
                                                            <a href="{{ $member->twitter }}" target="_blank" rel="noopener" class="contact-btn w-8 h-8 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800" title="X">x</a>
                                                        @endif
                                                        @if($member->instagram)
                                                            <a href="{{ $member->instagram }}" target="_blank" rel="noopener" class="contact-btn w-8 h-8 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center hover:bg-pink-200" title="Instagram">◎</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-16">
                                <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                                    <span class="text-4xl text-gray-400">👥</span>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('common.no_personnel') }}</h3>
                                <p class="text-gray-600 mb-8 leading-relaxed">{{ __('common.no_personnel_description') }}</p>
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
