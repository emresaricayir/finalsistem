<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sunucu Hatası - 500</title>

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
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-red-50 to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-8">
        <div class="text-center">
            <!-- Error Icon -->
            <div class="mx-auto h-32 w-32 text-red-500 mb-8">
                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Error Title -->
            <h1 class="text-4xl font-bold text-gray-900 mb-4">500</h1>
            <h2 class="text-2xl font-semibold text-red-600 mb-6">Sunucu Hatası</h2>

            <!-- Error Message -->
            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                Bir hata oluştu ve sayfa yüklenemedi.<br>
                Lütfen daha sonra tekrar deneyin veya ana sayfaya dönün.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Geri Dön
                </a>

                @auth
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-red-300 text-base font-medium rounded-xl text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </a>
                @else
                <a href="{{ route('welcome') }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-red-300 text-base font-medium rounded-xl text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-home mr-2"></i>
                    Ana Sayfa
                </a>
                @endauth
            </div>

            <!-- Additional Info -->
            <div class="mt-8 p-6 bg-white/80 backdrop-blur-sm border border-red-200 rounded-xl shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900 mb-1">Teknik Destek</h3>
                        <p class="text-sm text-gray-600">
                            Bu hata devam ederse, lütfen sistem yöneticisi ile iletişime geçin. Hata kodu: 500
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
