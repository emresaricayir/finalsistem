<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Şifre Sıfırlama - {{ config('app.name', 'Üyelik Sistemi') }}</title>
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
            <div class="w-20 h-20 bg-gradient-to-r from-amber-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Şifre Sıfırlama</h1>
            <p class="text-teal-100">E-posta adresinizi girin</p>
        </div>

        <!-- Reset Form -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            <!-- Info Message -->
            <div class="mb-6 bg-blue-500/20 border border-blue-400/30 text-blue-100 px-4 py-4 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle mr-3 mt-0.5 text-blue-300"></i>
                    <div class="text-sm">
                        <p class="font-medium mb-1">Şifrenizi mi unuttunuz?</p>
                        <p>E-posta adresinizi girin, size şifre sıfırlama bağlantısı gönderelim.</p>
                    </div>
                </div>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 bg-green-500/20 border border-green-400/30 text-green-100 px-4 py-4 rounded-xl">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle mr-3 mt-0.5 text-green-300"></i>
                        <div class="text-sm">
                            <p class="font-medium mb-1">E-posta Gönderildi!</p>
                            <p>{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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

                <!-- Send Reset Link Button -->
                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl focus:ring-4 focus:ring-amber-400/50"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        Sıfırlama Bağlantısı Gönder
                    </button>
                </div>
            </form>
        </div>

        <!-- Back to Login -->
        <div class="text-center mt-8 space-y-4">
            <div class="text-teal-100 text-sm">
                <i class="fas fa-lightbulb mr-2"></i>
                Şifrenizi hatırladınız mı?
            </div>
            <div class="flex justify-center">
                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl transition-all duration-300 font-medium border border-white/20"
                >
                    <i class="fas fa-arrow-left mr-2"></i>
                    Admin Girişi
                </a>
            </div>
        </div>
    </div>

    <script>
        // Add floating animation to background elements
        document.addEventListener('DOMContentLoaded', function() {
            const floatingElements = document.querySelectorAll('.animate-pulse');
            floatingElements.forEach((element, index) => {
                element.style.animationDelay = `${index * 0.5}s`;
                element.style.animationDuration = `${3 + index}s`;
            });
        });

        // Auto-hide success message after 10 seconds
        const successMessage = document.querySelector('.bg-green-500\\/20');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.5s ease-out';
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.remove();
                }, 500);
            }, 10000);
        }
    </script>
</body>
</html>
