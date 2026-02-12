<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.forgot_password_title') }} - {{ $settings['organization_name'] }}</title>

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
    
    @php
        // Tema renklerini al
        $themePrimaryColor = \App\Models\Settings::get('theme_primary_color', '#085952');
        $themeSecondaryColor = \App\Models\Settings::get('theme_secondary_color', '#0a7b73');
        $themeHoverColor = \App\Models\Settings::get('theme_hover_color', '#0f766e');
        $themeLinkColor = \App\Models\Settings::get('theme_link_color', '#0d9488');
        $themeGradientStart = \App\Models\Settings::get('theme_gradient_start', '#076961');
        $themeGradientEnd = \App\Models\Settings::get('theme_gradient_end', '#0a7b6e');
        $themeGradientDirection = \App\Models\Settings::get('theme_gradient_direction', 'to right');
        $themeButtonColor = \App\Models\Settings::get('theme_button_color', '#0d9488');
        $themeUseGradient = \App\Models\Settings::get('theme_use_gradient', false);
        $themeGradientCss = \App\Models\Settings::get('theme_gradient_css', 'linear-gradient(to right, #076961, #0a7b6e)');
    @endphp
    
    @include('partials.theme-styles')
</head>
<body class="bg-gray-50 font-sans">
    @include('partials.header-menu-wrapper')

    <!-- Main Content -->
    <main class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Success Messages -->
        @if(session('success'))
            <div class="bg-teal-50 border border-teal-200 text-teal-700 px-6 py-4 rounded-lg mb-8">
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

        <!-- Forgot Password Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                @if($settings['logo'])
                    <div class="w-16 h-16 mx-auto mb-4">
                        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="{{ $settings['organization_name'] }}" class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="w-16 h-16 bg-teal-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-key text-teal-600 text-2xl"></i>
                    </div>
                @endif
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('common.forgot_password_title') }}</h3>
                <div class="text-gray-600 space-y-1">
                    <p class="text-sm">{{ __('common.forgot_password_description') }}</p>
                </div>
            </div>

            <form action="{{ route('member.password.email') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-teal-600"></i>{{ __('common.email_address') }} *
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
                           placeholder="ornek@email.com">
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-teal-600 to-teal-700 text-white py-3 rounded-lg font-semibold text-lg hover:from-teal-700 hover:to-teal-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-paper-plane mr-2"></i>
                    {{ __('common.send_reset_link') }}
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

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('member.login') }}" class="inline-flex items-center px-6 py-3 border border-teal-600 text-teal-600 rounded-lg font-semibold hover:bg-teal-50 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('common.back_to_login') }}
                </a>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mt-8">
            <h4 class="text-lg font-bold text-blue-800 mb-4 text-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                {{ __('common.password_reset_info') }}
            </h4>
            <div class="space-y-4">
                <div class="text-center">
                    <p class="text-sm text-blue-700 mb-2">{{ __('common.password_reset_info_desc') }}</p>
                </div>
                <div class="text-xs text-blue-600 text-center space-y-1">
                    <p>• {{ __('common.link_valid_24_hours') }}</p>
                    <p>• {{ __('common.link_use_once') }}</p>
                    <p>• {{ __('common.check_spam_folder') }}</p>
                </div>
            </div>
        </div>

    </main>

    @include('partials.footer')
</body>
</html>
