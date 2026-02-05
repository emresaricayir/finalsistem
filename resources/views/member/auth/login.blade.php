<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.member_login_title') }} - {{ $settings['organization_name'] }}</title>

    <!-- Favicon -->
    @if(\App\Models\Settings::hasFavicon())
        <link rel="icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
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
                            950: '#042f2e'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Theme Styles -->
    @include('partials.theme-styles')
    
    <style>
        .theme-link-icon {
            color: var(--theme-link-color) !important;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--theme-link-color) !important;
            --tw-ring-color: var(--theme-link-color) !important;
            box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.2) !important;
        }
        input[type="checkbox"]:focus {
            --tw-ring-color: var(--theme-link-color) !important;
        }
        input[type="checkbox"]:checked {
            background-color: var(--theme-link-color) !important;
            border-color: var(--theme-link-color) !important;
        }
        .focus\:ring-teal-500:focus {
            --tw-ring-color: var(--theme-link-color) !important;
        }
        .focus\:border-teal-500:focus {
            border-color: var(--theme-link-color) !important;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans">
    @include('partials.top-header')
    @include('partials.main-menu')

    <!-- Main Content -->
    <main class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Success Messages -->
        @if(session('success'))
            <div class="px-6 py-4 rounded-lg mb-8" style="background-color: rgba(13, 148, 136, 0.05); border: 1px solid rgba(13, 148, 136, 0.2); color: var(--theme-hover-color);">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg mb-8">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold mb-2">{{ __('common.please_fix_errors') }}</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif



        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                @if($settings['logo'])
                    <div class="w-16 h-16 mx-auto mb-4">
                        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="{{ $settings['organization_name'] }}" class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background-color: rgba(13, 148, 136, 0.1);">
                        <i class="fas fa-user-circle text-2xl" style="color: var(--theme-link-color);"></i>
                    </div>
                @endif
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('common.welcome') }}</h3>
                <p class="text-gray-600">{{ __('common.member_panel_login') }}</p>
            </div>

            <form action="{{ route('member.login.submit') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 theme-link-icon"></i>{{ __('common.email_address') }} *
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg transition-colors"
                           placeholder="ornek@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 theme-link-icon"></i>{{ __('common.password') }} *
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg transition-colors pr-10"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i id="passwordToggle" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 border-gray-300 rounded theme-link-icon">
                        <label for="remember" class="ml-2 text-sm text-gray-600">{{ __('common.remember_me') }}</label>
                    </div>
                    <a href="{{ route('member.forgot-password') }}" class="text-sm transition-colors" style="color: var(--theme-link-color);" onmouseover="this.style.color='var(--theme-hover-color)'" onmouseout="this.style.color='var(--theme-link-color)'">{{ __('common.forgot_password') }}</a>
                </div>

                <button type="submit" class="w-full text-white py-3 rounded-lg font-semibold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105" style="background: var(--theme-gradient);" onmouseover="this.style.background='linear-gradient(var(--theme-gradient-direction), var(--theme-hover-color), var(--theme-gradient-end))';" onmouseout="this.style.background='var(--theme-gradient)';">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    {{ __('common.login') }}
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">{{ __('common.or') }}</span>
                    </div>
                </div>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-4">
                    {{ __('common.not_member_yet') }}
                </p>
                <a href="{{ route('member.application') }}" class="inline-flex items-center px-6 py-3 border rounded-lg font-semibold transition-all duration-200" style="border-color: var(--theme-link-color); color: var(--theme-link-color);" onmouseover="this.style.backgroundColor='rgba(13, 148, 136, 0.05)'; this.style.color='var(--theme-hover-color)';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--theme-link-color)';">
                    <i class="fas fa-user-plus mr-2"></i>
                    {{ __('common.apply_for_membership') }}
                </a>
            </div>
        </div>


    </main>

    @include('partials.footer')

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggle');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
