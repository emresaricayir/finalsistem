<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Sıfırla / Passwort Zurücksetzen - {{ $settings['organization_name'] }}</title>

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
</head>
<body class="bg-gray-50 font-sans">
    @include('partials.top-header')
    @include('partials.main-menu')

    <!-- Main Content -->
    <main class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12">

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
                        <h4 class="font-semibold mb-2">Lütfen aşağıdaki hataları düzeltin / Bitte korrigieren Sie die folgenden Fehler:</h4>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Reset Password Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8">
            <div class="text-center mb-8">
                @if($settings['logo'])
                    <div class="w-16 h-16 mx-auto mb-4">
                        <img src="{{ asset('storage/' . $settings['logo']) }}" alt="{{ $settings['organization_name'] }}" class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="w-16 h-16 bg-teal-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lock text-teal-600 text-2xl"></i>
                    </div>
                @endif
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Yeni Şifre Belirle / Neues Passwort festlegen</h3>
                <p class="text-gray-600">Güvenli bir şifre seçin / Wählen Sie ein sicheres Passwort</p>
            </div>

            <form action="{{ route('member.password.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-teal-600"></i>Yeni Şifre / Neues Passwort *
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors pr-10"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i id="passwordToggle" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">En az 6 karakter olmalıdır / Mindestens 6 Zeichen</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-teal-600"></i>Şifre Tekrar / Passwort wiederholen *
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors pr-10"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i id="passwordConfirmationToggle" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-teal-600 to-teal-700 text-white py-3 rounded-lg font-semibold text-lg hover:from-teal-700 hover:to-teal-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    Şifremi Güncelle / Passwort aktualisieren
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">veya / oder</span>
                    </div>
                </div>
            </div>

            <!-- Back to Login -->
            <div class="text-center">
                <a href="{{ route('member.login') }}" class="inline-flex items-center px-6 py-3 border border-teal-600 text-teal-600 rounded-lg font-semibold hover:bg-teal-50 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Giriş Sayfasına Dön / Zur Anmeldeseite zurück
                </a>
            </div>
        </div>

        <!-- Security Info -->
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mt-8">
            <h4 class="text-lg font-bold text-green-800 mb-4 text-center">
                <i class="fas fa-shield-alt mr-2 text-green-600"></i>
                Güvenlik Önerileri / Sicherheitsempfehlungen
            </h4>
            <div class="text-center">
                <div class="text-xs text-green-700 space-y-1">
                    <p>• Şifreniz en az 6 karakter olmalıdır / Ihr Passwort sollte mindestens 6 Zeichen haben</p>
                    <p>• Güçlü bir şifre için harf, rakam ve özel karakter kullanın / Verwenden Sie Buchstaben, Zahlen und Sonderzeichen für ein starkes Passwort</p>
                    <p>• Şifrenizi kimseyle paylaşmayın / Teilen Sie Ihr Passwort mit niemandem</p>
                    <p>• Düzenli olarak şifrenizi değiştirin / Ändern Sie Ihr Passwort regelmäßig</p>
                </div>
            </div>
        </div>

    </main>

    @include('partials.footer')

    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId + 'Toggle');

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
