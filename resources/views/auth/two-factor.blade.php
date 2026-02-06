<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>2FA Doğrulama - {{ config('app.name', 'Üyelik Sistemi') }}</title>
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
        .code-input {
            font-size: 1.5rem;
            letter-spacing: 0.5rem;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center px-4">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-32 h-32 rounded-full bg-white/20 animate-pulse"></div>
        <div class="absolute top-1/4 right-20 w-20 h-20 rounded-full bg-white/10 animate-pulse delay-1000"></div>
        <div class="absolute bottom-20 left-1/3 w-24 h-24 rounded-full bg-white/15 animate-pulse delay-500"></div>
    </div>

    <!-- 2FA Card -->
    <div class="w-full max-w-md relative z-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-teal-400 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <i class="fas fa-shield-alt text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">İki Faktörlü Doğrulama</h1>
            <p class="text-teal-100">Google Authenticator'dan kodunuzu girin</p>
        </div>

        <!-- 2FA Form -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8">
            @if ($errors->any())
                <div class="mb-6 bg-red-500/20 border border-red-400/30 text-red-100 px-4 py-3 rounded-xl">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-6">
                @csrf

                <!-- 2FA Code -->
                <div>
                    <label for="code" class="block text-sm font-semibold text-white mb-3 text-center">
                        <i class="fas fa-key mr-2 text-teal-300"></i>6 Haneli Güvenlik Kodu
                    </label>
                    <input
                        id="code"
                        type="text"
                        name="code"
                        required
                        autofocus
                        maxlength="6"
                        pattern="[0-9]{6}"
                        inputmode="numeric"
                        class="w-full px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/60 focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-300 input-glow code-input"
                        placeholder="000000"
                        autocomplete="one-time-code"
                    >
                    <p class="mt-3 text-center text-sm text-teal-200">
                        <i class="fas fa-info-circle mr-1"></i>
                        Google Authenticator uygulamanızdan 6 haneli kodu girin
                    </p>
                    <p class="mt-2 text-center text-xs text-teal-300">
                        Veya recovery kodunuzu kullanabilirsiniz
                    </p>
                </div>

                <!-- Verify Button -->
                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl focus:ring-4 focus:ring-teal-400/50"
                    >
                        <i class="fas fa-check-circle mr-2"></i>
                        Doğrula ve Giriş Yap
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="text-center pt-4">
                    <a
                        href="{{ route('login') }}"
                        class="text-sm text-teal-200 hover:text-white transition-colors duration-200 underline"
                    >
                        <i class="fas fa-arrow-left mr-1"></i>
                        Giriş sayfasına dön
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-focus and auto-advance for code input
        document.addEventListener('DOMContentLoaded', function() {
            const codeInput = document.getElementById('code');
            
            // Only allow numbers
            codeInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Auto-submit when 6 digits are entered
            codeInput.addEventListener('input', function(e) {
                if (this.value.length === 6) {
                    // Small delay for better UX
                    setTimeout(() => {
                        this.form.submit();
                    }, 300);
                }
            });
        });
    </script>
</body>
</html>
