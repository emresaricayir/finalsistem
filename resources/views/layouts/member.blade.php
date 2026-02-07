<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ $settings['organization_name'] }}</title>
    
    <!-- Favicon -->
    @if(\App\Models\Settings::hasFavicon())
        <link rel="icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
    @endif
    <link href="//fonts.googleapis.com/css?family=Titillium+Web:400,600&amp;subset=latin-ext" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'teal': {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'pulse-slow': 'pulse 3s infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-effect-strong {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 50%, #134e4a 100%);
        }

                .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }



        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Mobile Menu Animations */
        .mobile-menu-enter {
            animation: slideDown 0.3s ease-out;
        }

        .mobile-menu-exit {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        /* Hamburger Icon Animation */
        .hamburger-icon {
            transition: transform 0.3s ease;
        }

        .hamburger-icon.rotate {
            transform: rotate(90deg);
        }
    </style>
    @yield('styles')
</head>
<body class="min-h-screen gradient-bg custom-scrollbar" style="font-family: 'Titillium Web', sans-serif;">
    <!-- Header -->
    <div class="glass-effect-strong backdrop-blur-xl border-b border-white/20 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center py-4 lg:py-6 space-y-4 lg:space-y-0">
                <div class="flex items-center w-full lg:w-auto">
                    @if(\App\Models\Settings::hasLogo())
                        <img src="{{ \App\Models\Settings::getLogoUrl() }}"
                             alt="{{ $settings['organization_name'] }}"
                             class="w-12 h-12 sm:w-16 sm:h-16 object-contain bg-white rounded-xl p-1 mr-3 sm:mr-4 shadow-xl">
                    @else
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-teal-400 to-teal-600 rounded-xl flex items-center justify-center mr-3 sm:mr-4 shadow-xl">
                            <i class="fas fa-mosque text-white text-lg sm:text-2xl"></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h1 class="text-lg sm:text-2xl font-bold text-white mb-1 truncate">{{ $settings['organization_name'] }}</h1>
                        <p class="text-xs sm:text-sm text-teal-100 font-medium">
                            {{ __('common.member_panel') }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4 lg:space-x-6 w-full lg:w-auto">
                    <div class="text-left sm:text-right">
                        <p class="text-xs sm:text-sm text-teal-100 font-medium">
                            {{ __('common.welcome') }}
                        </p>
                        <p class="font-bold text-white text-sm sm:text-lg truncate max-w-48 sm:max-w-none">{{ $member->name }} {{ $member->surname }}</p>
                    </div>
                    <form action="{{ route('member.logout') }}" method="POST" class="inline hidden lg:block">
                        @csrf
                        <button type="submit" class="bg-red-500/20 hover:bg-red-500/30 text-red-100 hover:text-white px-3 py-2 rounded-xl transition-all duration-300 font-medium border border-red-400/30 hover:shadow-lg hover:shadow-red-500/25" title="{{ __('common.logout') }}">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                    <!-- Language Switcher - En sağda -->
                    <div class="flex items-center space-x-0 bg-white/10 backdrop-blur-sm rounded-lg border border-white/20 shadow-sm overflow-hidden ml-auto">
                        <a href="{{ route('language.switch', 'tr') }}" 
                           class="flex items-center space-x-1.5 px-3 py-2 transition-all duration-200 {{ app()->getLocale() === 'tr' ? 'text-white bg-white/20' : 'text-teal-100 hover:bg-white/10' }}"
                           title="Türkçe">
                            <img src="{{ asset('storage/templates/tr.png') }}" alt="Türkçe" class="w-4 h-3 object-cover rounded-sm">
                            <span class="text-xs font-semibold">TR</span>
                        </a>
                        <span class="w-px h-4 bg-white/30"></span>
                        <a href="{{ route('language.switch', 'de') }}" 
                           class="flex items-center space-x-1.5 px-3 py-2 transition-all duration-200 {{ app()->getLocale() === 'de' ? 'text-white bg-white/20' : 'text-teal-100 hover:bg-white/10' }}"
                           title="Deutsch">
                            <img src="{{ asset('storage/templates/de.png') }}" alt="Deutsch" class="w-4 h-3 object-cover rounded-sm">
                            <span class="text-xs font-semibold">DE</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="glass-effect backdrop-blur-lg border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile Menu Button -->
            <div class="flex justify-between items-center py-4 lg:hidden">
                <h2 class="text-lg font-bold text-white">{{ __('common.menu') }}</h2>
                <button id="mobile-menu-button" class="text-teal-100 hover:text-white focus:outline-none focus:text-white transition-colors duration-300 p-2">
                    <i class="fas fa-bars text-xl hamburger-icon"></i>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex flex-row gap-3 md:gap-8 py-6">
                <a href="{{ route('member.dashboard') }}"
                   class="@if(request()->routeIs('member.dashboard')) bg-teal-500/30 text-white border-b-2 border-teal-400 shadow-lg @else text-teal-100 hover:text-white hover:bg-white/10 hover:shadow-lg @endif pb-3 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                    <i class="fas fa-home mr-2"></i>
                    {{ __('common.member_dashboard') }}
                </a>
                <a href="{{ route('member.profile') }}"
                   class="@if(request()->routeIs('member.profile')) bg-teal-500/30 text-white border-b-2 border-teal-400 shadow-lg @else text-teal-100 hover:text-white hover:bg-white/10 hover:shadow-lg @endif pb-3 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    {{ __('common.member_profile') }}
                </a>
                <a href="{{ route('member.payments') }}"
                   class="@if(request()->routeIs('member.payments')) bg-teal-500/30 text-white border-b-2 border-teal-400 shadow-lg @else text-teal-100 hover:text-white hover:bg-white/10 hover:shadow-lg @endif pb-3 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                    <i class="fas fa-receipt mr-2"></i>
                    {{ __('common.member_payments') }}
                </a>
                @if($member->application_status === 'approved')
                <a href="{{ route('member.application.html') }}"
                    class="text-teal-100 hover:text-white hover:bg-white/10 pb-3 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:shadow-lg flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    {{ __('common.member_certificate') }}
                </a>
                @endif
            </nav>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden lg:hidden pb-4">
                <nav class="flex flex-col space-y-2">
                    <a href="{{ route('member.dashboard') }}"
                       class="@if(request()->routeIs('member.dashboard')) bg-teal-500/30 text-white border-l-4 border-teal-400 @else text-teal-100 hover:text-white hover:bg-white/10 @endif px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                        <i class="fas fa-home mr-3 text-base"></i>
                        {{ __('common.member_dashboard') }}
                    </a>
                    <a href="{{ route('member.profile') }}"
                       class="@if(request()->routeIs('member.profile')) bg-teal-500/30 text-white border-l-4 border-teal-400 @else text-teal-100 hover:text-white hover:bg-white/10 @endif px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                        <i class="fas fa-user mr-3 text-base"></i>
                        {{ __('common.member_profile') }}
                    </a>
                    <a href="{{ route('member.payments') }}"
                       class="@if(request()->routeIs('member.payments')) bg-teal-500/30 text-white border-l-4 border-teal-400 @else text-teal-100 hover:text-white hover:bg-white/10 @endif px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                        <i class="fas fa-receipt mr-3 text-base"></i>
                        {{ __('common.member_payments') }}
                    </a>
                    @if($member->application_status === 'approved')
                    <a href="{{ route('member.application.pdf', $member->id) }}?preview=1"
                        target="_blank"
                        class="text-teal-100 hover:text-white hover:bg-white/10 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                        <i class="fas fa-file-pdf mr-3 text-base"></i>
                        {{ __('common.member_certificate') }}
                    </a>
                    @endif
                    
                    <!-- Çıkış Butonu - Mobil Menü En Altında -->
                    <div class="mt-4 pt-4 border-t border-white/20">
                        <form action="{{ route('member.logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="w-full bg-red-500/20 hover:bg-red-500/30 text-red-100 hover:text-white px-4 py-3 rounded-xl transition-all duration-300 font-medium border border-red-400/30 hover:shadow-lg hover:shadow-red-500/25 flex items-center justify-center" title="{{ __('common.logout') }}">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="@yield('content-class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12')">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="mt-10 sm:mt-20 py-6 sm:py-12 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-teal-100 text-sm sm:text-base font-medium">
                    © {{ date('Y') }} {{ $settings['organization_name'] }}. Tüm hakları saklıdır.
                    <span class="text-xs text-teal-200 ml-1 sm:ml-2">(Alle Rechte vorbehalten)</span>
                </p>
            </div>
        </div>
    </footer>

    @yield('scripts')

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    const isHidden = mobileMenu.classList.contains('hidden');

                    if (isHidden) {
                        // Show menu
                        mobileMenu.classList.remove('hidden');
                        mobileMenu.classList.add('mobile-menu-enter');
                        mobileMenu.classList.remove('mobile-menu-exit');

                        // Change icon to X
                        const icon = mobileMenuButton.querySelector('i');
                        icon.className = 'fas fa-times text-xl hamburger-icon';
                        icon.classList.add('rotate');
                    } else {
                        // Hide menu
                        mobileMenu.classList.add('mobile-menu-exit');
                        mobileMenu.classList.remove('mobile-menu-enter');

                        setTimeout(() => {
                            mobileMenu.classList.add('hidden');
                            mobileMenu.classList.remove('mobile-menu-exit');
                        }, 300);

                        // Change icon to hamburger
                        const icon = mobileMenuButton.querySelector('i');
                        icon.className = 'fas fa-bars text-xl hamburger-icon';
                        icon.classList.remove('rotate');
                    }
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                        if (!mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.add('mobile-menu-exit');
                            mobileMenu.classList.remove('mobile-menu-enter');

                            setTimeout(() => {
                                mobileMenu.classList.add('hidden');
                                mobileMenu.classList.remove('mobile-menu-exit');
                            }, 300);

                            const icon = mobileMenuButton.querySelector('i');
                            icon.className = 'fas fa-bars text-xl hamburger-icon';
                            icon.classList.remove('rotate');
                        }
                    }
                });

                // Close menu when window is resized to desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 640) { // sm breakpoint
                        if (!mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.add('hidden');
                            mobileMenu.classList.remove('mobile-menu-enter', 'mobile-menu-exit');

                            const icon = mobileMenuButton.querySelector('i');
                            icon.className = 'fas fa-bars text-xl hamburger-icon';
                            icon.classList.remove('rotate');
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
