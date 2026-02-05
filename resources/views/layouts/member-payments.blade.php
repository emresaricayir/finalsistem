<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ $settings['organization_name'] }}</title>
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
                    }
                }
            }
        }
    </script>
    <style>
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

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
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

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.5);
        }
    </style>
    @yield('styles')
</head>
<body class="min-h-screen bg-white custom-scrollbar" style="font-family: 'Titillium Web', sans-serif;">
    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-800 to-teal-900 border-b border-teal-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    @if(\App\Models\Settings::hasLogo())
                        <img src="{{ \App\Models\Settings::getLogoUrl() }}"
                             alt="{{ $settings['organization_name'] }}"
                             class="w-16 h-16 object-contain bg-white rounded-xl p-1 mr-4 shadow-xl">
                    @else
                        <div class="w-16 h-16 bg-gradient-to-r from-teal-400 to-teal-600 rounded-xl flex items-center justify-center mr-4 shadow-xl">
                            <i class="fas fa-mosque text-white text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-white mb-1">{{ $settings['organization_name'] }}</h1>
                        <p class="text-sm text-teal-100 font-medium">Üye Paneli</p>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="hidden md:block text-right">
                        <p class="text-sm text-teal-100 font-medium">Hoş geldiniz</p>
                        <p class="font-bold text-white text-lg">{{ $member->name }} {{ $member->surname }}</p>
                    </div>
                    <form action="{{ route('member.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500/20 hover:bg-red-500/30 text-red-100 hover:text-white px-6 py-3 rounded-xl transition-all duration-300 font-medium border border-red-400/30 hover:shadow-lg hover:shadow-red-500/25">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Çıkış
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="bg-teal-700 border-b border-teal-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Mobile Menu Button -->
            <div class="flex justify-between items-center py-4 lg:hidden">
                <h2 class="text-lg font-bold text-white">Menü</h2>
                <button id="mobile-menu-button" class="text-teal-100 hover:text-white focus:outline-none focus:text-white transition-colors duration-300 p-2">
                    <i class="fas fa-bars text-xl hamburger-icon"></i>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex flex-row gap-3 md:gap-8 py-6">
                <a href="{{ route('member.dashboard') }}"
                   class="text-teal-100 hover:text-white hover:bg-white/10 pb-3 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:shadow-lg flex items-center">
                    <i class="fas fa-home mr-2"></i>
                    Ana Sayfa
                    <span class="text-xs ml-1">(Startseite)</span>
                </a>
                <a href="{{ route('member.profile') }}"
                   class="text-teal-100 hover:text-white hover:bg-white/10 pb-3 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:shadow-lg flex items-center">
                    <i class="fas fa-user mr-2"></i>
                    Profil
                    <span class="text-xs ml-1">(Profil)</span>
                </a>
                <a href="{{ route('member.payments') }}"
                   class="bg-teal-500/30 text-white border-b-2 border-teal-400 shadow-lg pb-3 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                    <i class="fas fa-receipt mr-2"></i>
                    Ödemelerim
                    <span class="text-xs ml-1">(Meine Zahlungen)</span>
                </a>
                <a href="{{ route('member.contact') }}"
                   class="text-teal-100 hover:text-white hover:bg-white/10 pb-3 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:shadow-lg flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    İletişim & Bağış
                    <span class="text-xs ml-1">(Kontakt & Spende)</span>
                </a>
                @if($member->application_status === 'approved')
                <a href="{{ route('member.application.pdf', $member->id) }}?preview=1"
                    target="_blank"
                    class="text-teal-100 hover:text-white hover:bg-white/10 pb-3 px-6 py-3 rounded-xl text-sm font-medium transition-all duration-300 hover:shadow-lg flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Üyelik Belgesi
                    <span class="text-xs ml-1">(Mitgliedsausweis)</span>
                </a>
                @endif
            </nav>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="hidden lg:hidden pb-4">
                <nav class="flex flex-col space-y-2">
                    <a href="{{ route('member.dashboard') }}"
                       class="text-teal-100 hover:text-white hover:bg-white/10 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                        <i class="fas fa-home mr-3 text-base"></i>
                        Ana Sayfa
                        <span class="text-xs ml-2">(Startseite)</span>
                    </a>
                    <a href="{{ route('member.profile') }}"
                       class="text-teal-100 hover:text-white hover:bg-white/10 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                        <i class="fas fa-user mr-3 text-base"></i>
                        Profil
                        <span class="text-xs ml-2">(Profil)</span>
                    </a>
                    <a href="{{ route('member.payments') }}"
                       class="bg-teal-500/30 text-white border-l-4 border-teal-400 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                        <i class="fas fa-receipt mr-3 text-base"></i>
                        Ödemelerim
                        <span class="text-xs ml-2">(Meine Zahlungen)</span>
                    </a>
                    <a href="{{ route('member.contact') }}"
                       class="text-teal-100 hover:text-white hover:bg-white/10 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                        <i class="fas fa-phone mr-3 text-base"></i>
                        İletişim & Bağış
                        <span class="text-xs ml-2">(Kontakt & Spende)</span>
                    </a>
                    @if($member->application_status === 'approved')
                    <a href="{{ route('member.application.pdf', $member->id) }}?preview=1"
                        target="_blank"
                        class="text-teal-100 hover:text-white hover:bg-white/10 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-300 flex items-center">
                        <i class="fas fa-file-pdf mr-3 text-base"></i>
                        Üyelik Belgesi
                        <span class="text-xs ml-2">(Mitgliedsausweis)</span>
                    </a>
                    @endif
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="@yield('content-class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12')">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="mt-20 py-12 border-t border-gray-200 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-600 text-base font-medium">
                    © {{ date('Y') }} {{ $settings['organization_name'] }}. Tüm hakları saklıdır.
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
