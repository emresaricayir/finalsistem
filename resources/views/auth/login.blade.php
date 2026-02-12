@php
    $orgName = \App\Models\Settings::get('organization_name', 'Admin Paneli');
    $orgSubtitle = \App\Models\Settings::get('organization_subtitle');
    $logoUrl = \App\Models\Settings::getLogoUrl();
    $hasLogo = \App\Models\Settings::hasLogo();
    $backgroundImage = asset('storage/login/login-background.jpg');
@endphp

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Girişi - {{ $orgName }}</title>
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
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .gradient-overlay {
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.85) 0%, rgba(17, 94, 89, 0.85) 50%, rgba(19, 78, 74, 0.85) 100%);
        }
        .background-image {
            background-image: url('{{ $backgroundImage }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .input-glow:focus {
            box-shadow: 0 0 20px rgba(20, 184, 166, 0.3);
        }
        @media (max-width: 768px) {
            .background-image {
                background-size: cover;
                background-position: center;
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 relative overflow-hidden">
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 background-image">
        <div class="absolute inset-0 gradient-overlay"></div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-10 left-10 w-32 h-32 rounded-full bg-white/10 animate-pulse"></div>
        <div class="absolute top-1/4 right-20 w-20 h-20 rounded-full bg-white/5 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-20 left-1/3 w-24 h-24 rounded-full bg-white/8 animate-pulse" style="animation-delay: 0.5s;"></div>
        <div class="absolute bottom-1/4 right-1/4 w-16 h-16 rounded-full bg-white/10 animate-pulse" style="animation-delay: 0.7s;"></div>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md relative z-10">
        <!-- Header -->
        <div class="text-center mb-8">
            @if($hasLogo)
                <div class="w-24 h-24 mx-auto mb-1 shadow-2xl flex items-center justify-center">
                    <img src="{{ $logoUrl }}" alt="{{ $orgName }}" class="w-full h-full object-contain">
                </div>
            @else
                <div class="w-24 h-24 bg-gradient-to-r from-teal-400 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-3 shadow-2xl">
                    <i class="fas fa-mosque text-white text-3xl"></i>
                </div>
            @endif
            <h1 class="text-3xl font-bold text-white mb-2">{{ $orgName }}</h1>
            <p class="text-teal-100">Yönetici girişi yapın</p>
        </div>

        <!-- Login Form -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 bg-green-500/20 border border-green-400/30 text-green-100 px-4 py-3 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-white mb-3">
                        <i class="fas fa-envelope mr-2 text-teal-300"></i>E-posta Adresi
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/60 focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-300 input-glow"
                        placeholder="admin@example.com"
                    >
                    @if ($errors->get('email'))
                        <div class="mt-2 text-red-300 text-sm">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-white mb-3">
                        <i class="fas fa-lock mr-2 text-teal-300"></i>Şifre
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/60 focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-300 input-glow pr-12"
                            placeholder="••••••••"
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white/60 hover:text-white transition-colors duration-200"
                        >
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @if ($errors->get('password'))
                        <div class="mt-2 text-red-300 text-sm">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <label for="remember_me" class="flex items-center cursor-pointer">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 text-teal-500 bg-white/10 border-white/20 rounded focus:ring-teal-400 focus:ring-2"
                        >
                        <span class="ml-3 text-sm text-white">Beni hatırla</span>
                    </label>
                </div>

                <!-- Login Button -->
                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl focus:ring-4 focus:ring-teal-400/50"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Giriş Yap
                    </button>
                </div>

                <!-- Forgot Password -->
                @if (Route::has('password.request'))
                    <div class="text-center pt-4">
                        <a
                            href="{{ route('password.request') }}"
                            class="text-sm text-teal-200 hover:text-white transition-colors duration-200 underline"
                        >
                            <i class="fas fa-question-circle mr-1"></i>
                            Şifremi unuttum
                        </a>
                    </div>
                @endif
            </form>
        </div>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Add floating animation to background elements
        document.addEventListener('DOMContentLoaded', function() {
            const floatingElements = document.querySelectorAll('.animate-pulse');
            floatingElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.5}s`;
                element.style.animationDuration = `${3 + index}s`;
            });
        });
    </script>
</body>
</html>
