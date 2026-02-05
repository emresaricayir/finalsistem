<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yeni Şifre Oluştur - {{ config('app.name', 'Üyelik Sistemi') }}</title>
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
        .gradient-bg {
            background: linear-gradient(135deg, #0f766e 0%, #115e59 50%, #134e4a 100%);
        }
        .input-glow:focus {
            box-shadow: 0 0 20px rgba(20, 184, 166, 0.3);
        }
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center px-4">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-32 h-32 rounded-full bg-white/20 animate-pulse"></div>
        <div class="absolute top-1/4 right-20 w-20 h-20 rounded-full bg-white/10 animate-pulse delay-1000"></div>
        <div class="absolute bottom-20 left-1/3 w-24 h-24 rounded-full bg-white/15 animate-pulse delay-500"></div>
        <div class="absolute bottom-1/4 right-1/4 w-16 h-16 rounded-full bg-white/20 animate-pulse delay-700"></div>
    </div>

    <!-- Reset Password Card -->
    <div class="w-full max-w-md relative z-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <i class="fas fa-shield-alt text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Yeni Şifre Oluştur</h1>
            <p class="text-teal-100">Güvenli bir şifre belirleyin</p>
        </div>

        <!-- Reset Form -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            <!-- Info Message -->
            <div class="mb-6 bg-blue-500/20 border border-blue-400/30 text-blue-100 px-4 py-4 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle mr-3 mt-0.5 text-blue-300"></i>
                    <div class="text-sm">
                        <p class="font-medium mb-1">Şifre Güvenliği</p>
                        <p>En az 8 karakter, büyük-küçük harf ve sayı kullanın.</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-white mb-3">
                        <i class="fas fa-envelope mr-2 text-teal-300"></i>E-posta Adresi
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/60 focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-300 input-glow"
                        readonly
                    >
                    @if ($errors->get('email'))
                        <div class="mt-2 text-red-300 text-sm">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-white mb-3">
                        <i class="fas fa-lock mr-2 text-teal-300"></i>Yeni Şifre
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            oninput="checkPasswordStrength()"
                            class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/60 focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-300 input-glow pr-12"
                            placeholder="••••••••"
                        >
                        <button
                            type="button"
                            onclick="togglePassword('password')"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white/60 hover:text-white transition-colors duration-200"
                        >
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="password-strength bg-gray-600" id="strength-bar"></div>
                        <div class="text-xs text-white/70 mt-1" id="strength-text">Şifre gücü</div>
                    </div>
                    @if ($errors->get('password'))
                        <div class="mt-2 text-red-300 text-sm">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-white mb-3">
                        <i class="fas fa-lock mr-2 text-teal-300"></i>Şifre Tekrarı
                    </label>
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            oninput="checkPasswordMatch()"
                            class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/60 focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-300 input-glow pr-12"
                            placeholder="••••••••"
                        >
                        <button
                            type="button"
                            onclick="togglePassword('password_confirmation')"
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white/60 hover:text-white transition-colors duration-200"
                        >
                            <i id="password_confirmation-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="mt-2 text-xs" id="match-indicator"></div>
                    @if ($errors->get('password_confirmation'))
                        <div class="mt-2 text-red-300 text-sm">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $errors->first('password_confirmation') }}
                        </div>
                    @endif
                </div>

                <!-- Reset Password Button -->
                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl focus:ring-4 focus:ring-green-400/50"
                    >
                        <i class="fas fa-shield-alt mr-2"></i>
                        Şifreyi Güncelle
                    </button>
                </div>
            </form>
        </div>

        <!-- Back to Login -->
        <div class="text-center mt-8">
            <a
                href="{{ route('login') }}"
                class="inline-flex items-center text-teal-200 hover:text-white transition-colors duration-200 text-sm"
            >
                <i class="fas fa-arrow-left mr-2"></i>
                Giriş sayfasına dön
            </a>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(inputId + '-icon');

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

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');

            let strength = 0;
            let feedback = [];

            if (password.length >= 8) strength += 1;
            else feedback.push('En az 8 karakter');

            if (password.match(/[a-z]/)) strength += 1;
            else feedback.push('Küçük harf');

            if (password.match(/[A-Z]/)) strength += 1;
            else feedback.push('Büyük harf');

            if (password.match(/[0-9]/)) strength += 1;
            else feedback.push('Sayı');

            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
            else feedback.push('Özel karakter');

            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];
            const texts = ['Çok Zayıf', 'Zayıf', 'Orta', 'İyi', 'Güçlü'];

            strengthBar.className = `password-strength ${colors[strength - 1] || 'bg-gray-600'}`;
            strengthBar.style.width = `${(strength / 5) * 100}%`;

            if (password.length > 0) {
                strengthText.textContent = texts[strength - 1] || 'Çok Zayıf';
                if (feedback.length > 0) {
                    strengthText.textContent += ` (Eksik: ${feedback.join(', ')})`;
                }
            } else {
                strengthText.textContent = 'Şifre gücü';
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const indicator = document.getElementById('match-indicator');

            if (confirmation.length === 0) {
                indicator.textContent = '';
                return;
            }

            if (password === confirmation) {
                indicator.innerHTML = '<i class="fas fa-check text-green-400 mr-1"></i><span class="text-green-300">Şifreler eşleşiyor</span>';
            } else {
                indicator.innerHTML = '<i class="fas fa-times text-red-400 mr-1"></i><span class="text-red-300">Şifreler eşleşmiyor</span>';
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
