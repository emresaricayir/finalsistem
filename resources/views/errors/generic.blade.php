<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exception->getStatusCode() ?? 'Hata' }} - {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
    </style>
</head>
<body class="min-h-screen">
    @php
        $statusCode = $exception->getStatusCode() ?? 500;
        $errorMessages = [
            400 => ['title' => 'Geçersiz İstek', 'message' => 'Yaptığınız istek geçersiz veya eksik bilgi içeriyor.', 'icon' => 'fa-exclamation-circle', 'color' => 'blue'],
            401 => ['title' => 'Yetkisiz Erişim', 'message' => 'Bu sayfaya erişmek için giriş yapmanız gerekiyor.', 'icon' => 'fa-user-lock', 'color' => 'yellow'],
            402 => ['title' => 'Ödeme Gerekli', 'message' => 'Bu işlem için ödeme yapmanız gerekiyor.', 'icon' => 'fa-credit-card', 'color' => 'orange'],
            403 => ['title' => 'Erişim Reddedildi', 'message' => 'Bu sayfaya erişim yetkiniz bulunmuyor.', 'icon' => 'fa-lock', 'color' => 'purple'],
            404 => ['title' => 'Sayfa Bulunamadı', 'message' => 'Aradığınız sayfa mevcut değil veya taşınmış olabilir.', 'icon' => 'fa-search', 'color' => 'red'],
            405 => ['title' => 'Yöntem İzinli Değil', 'message' => 'Bu işlem için kullanılan yöntem desteklenmiyor.', 'icon' => 'fa-ban', 'color' => 'red'],
            408 => ['title' => 'Zaman Aşımı', 'message' => 'İstek zaman aşımına uğradı. Lütfen tekrar deneyin.', 'icon' => 'fa-clock', 'color' => 'orange'],
            429 => ['title' => 'Çok Fazla İstek', 'message' => 'Çok fazla istek gönderdiniz. Lütfen biraz bekleyin.', 'icon' => 'fa-hourglass-half', 'color' => 'orange'],
            500 => ['title' => 'Sunucu Hatası', 'message' => 'Sunucumuzda beklenmeyen bir hata oluştu.', 'icon' => 'fa-exclamation-triangle', 'color' => 'orange'],
            502 => ['title' => 'Geçersiz Ağ Geçidi', 'message' => 'Sunucu geçici olarak kullanılamıyor.', 'icon' => 'fa-server', 'color' => 'orange'],
            503 => ['title' => 'Hizmet Kullanılamıyor', 'message' => 'Hizmet geçici olarak bakımda.', 'icon' => 'fa-tools', 'color' => 'blue'],
            504 => ['title' => 'Ağ Geçidi Zaman Aşımı', 'message' => 'Sunucu yanıt vermiyor. Lütfen tekrar deneyin.', 'icon' => 'fa-hourglass-end', 'color' => 'orange'],
        ];

        $error = $errorMessages[$statusCode] ?? [
            'title' => 'Bir Hata Oluştu',
            'message' => 'Beklenmeyen bir hata oluştu. Lütfen tekrar deneyin.',
            'icon' => 'fa-exclamation-triangle',
            'color' => 'gray'
        ];

        $colorClasses = [
            'blue' => ['bg' => 'from-blue-100 to-blue-200', 'text' => 'text-blue-500', 'button' => 'bg-blue-600 hover:bg-blue-700'],
            'yellow' => ['bg' => 'from-yellow-100 to-yellow-200', 'text' => 'text-yellow-500', 'button' => 'bg-yellow-600 hover:bg-yellow-700'],
            'orange' => ['bg' => 'from-orange-100 to-orange-200', 'text' => 'text-orange-500', 'button' => 'bg-orange-600 hover:bg-orange-700'],
            'red' => ['bg' => 'from-red-100 to-red-200', 'text' => 'text-red-500', 'button' => 'bg-red-600 hover:bg-red-700'],
            'purple' => ['bg' => 'from-purple-100 to-purple-200', 'text' => 'text-purple-500', 'button' => 'bg-purple-600 hover:bg-purple-700'],
            'gray' => ['bg' => 'from-gray-100 to-gray-200', 'text' => 'text-gray-500', 'button' => 'bg-gray-600 hover:bg-gray-700'],
        ];

        $colorClass = $colorClasses[$error['color']];
    @endphp

    @include('partials.header-menu-wrapper')

    <!-- Main Content -->
    <div class="relative min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-center min-h-[60vh]">
                <div class="max-w-md w-full text-center">
                    <!-- Error Icon -->
                    <div class="mb-8">
                        <div class="w-32 h-32 mx-auto bg-gradient-to-br {{ $colorClass['bg'] }} rounded-full flex items-center justify-center shadow-lg">
                            <i class="fas {{ $error['icon'] }} text-6xl {{ $colorClass['text'] }}"></i>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <h1 class="text-6xl font-bold text-gray-900 mb-4">{{ $statusCode }}</h1>
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">{{ $error['title'] }}</h2>
                    <p class="text-gray-600 mb-8 leading-relaxed">
                        {{ $error['message'] }}
                    </p>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        @if($statusCode == 500)
                            <button onclick="window.location.reload()" class="inline-flex items-center justify-center w-full {{ $colorClass['button'] }} text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-redo mr-2"></i>
                                Sayfayı Yenile
                            </button>
                        @endif

                        <a href="/" class="inline-flex items-center justify-center w-full bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-home mr-2"></i>
                            Ana Sayfaya Dön
                        </a>
                    </div>

                    <!-- Help Text -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-2"></i>
                            Sorun devam ederse
                            <a href="mailto:{{ \App\Models\Settings::get('organization_email') }}" class="font-medium underline hover:text-gray-800 transition-colors">
                                destek ekibimizle
                            </a>
                            iletişime geçin.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>



