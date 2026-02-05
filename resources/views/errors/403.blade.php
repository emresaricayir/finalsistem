<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Erişim Reddedildi - 403</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-yellow-50 to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-8">
        <div class="text-center">
            <!-- Error Icon -->
            <div class="mx-auto h-32 w-32 text-yellow-500 mb-8">
                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>

            <!-- Error Title -->
            <h1 class="text-4xl font-bold text-gray-900 mb-4">403</h1>
            <h2 class="text-2xl font-semibold text-yellow-600 mb-6">Erişim Reddedildi</h2>

            <!-- Error Message -->
            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                {{ $message ?? 'Bu alana girme yetkiniz yoktur.' }}<br>
                Lütfen giriş yapın veya yetkili bir kullanıcı olarak erişim sağlayın.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-yellow-600 to-yellow-500 hover:from-yellow-500 hover:to-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Geri Dön
                </a>

                @auth
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-yellow-300 text-base font-medium rounded-xl text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </a>
                @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-yellow-300 text-base font-medium rounded-xl text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Giriş Yap
                </a>
                @endauth
            </div>

            <!-- Additional Info -->
            <div class="mt-8 p-6 bg-white/80 backdrop-blur-sm border border-yellow-200 rounded-xl shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-shield-alt text-yellow-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900 mb-1">Yetki Gerekli</h3>
                        <p class="text-sm text-gray-600">
                            Bu sayfaya erişim için gerekli yetkiniz bulunmamaktadır. Eğer bu bir hata olduğunu düşünüyorsanız, sistem yöneticisi ile iletişime geçin.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
