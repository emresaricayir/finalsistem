<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sayfa Bulunamadı - 404</title>

    <!-- Favicon -->
    @if(\App\Models\Settings::hasFavicon())
        <link rel="icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
    @endif

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
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-lg w-full space-y-8">
        <div class="text-center">
            <!-- Error Icon -->
            <div class="mx-auto h-32 w-32 text-teal-500 mb-8">
                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>

            <!-- Error Title -->
            <h1 class="text-4xl font-bold text-gray-900 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-teal-600 mb-6">Sayfa Bulunamadı</h2>

            <!-- Error Message -->
            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                Aradığınız sayfa mevcut değil veya taşınmış olabilir.<br>
                Lütfen URL'yi kontrol edin veya ana sayfaya dönün.
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url()->previous() }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-500 hover:to-teal-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Geri Dön
                </a>

                @auth
                <a href="{{ route('admin.dashboard') }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-teal-300 text-base font-medium rounded-xl text-teal-700 bg-white hover:bg-teal-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </a>
                @else
                <a href="{{ route('welcome') }}"
                   class="inline-flex items-center justify-center px-6 py-3 border border-teal-300 text-base font-medium rounded-xl text-teal-700 bg-white hover:bg-teal-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-all duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-home mr-2"></i>
                    Ana Sayfa
                </a>
                @endauth
            </div>

            <!-- Additional Info -->
            <div class="mt-8 p-6 bg-white/80 backdrop-blur-sm border border-teal-200 rounded-xl shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-teal-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900 mb-1">Yardım</h3>
                        <p class="text-sm text-gray-600">
                            URL'yi kontrol edin veya ana sayfaya dönün. Sorun devam ederse bizimle iletişime geçin.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
