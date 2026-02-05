<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TV Ekranı - Üye Ödeme Durumu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden; /* TV ekranı için scroll kaldırıldı */
            height: 100vh;
        }

        .tv-container {
            width: 100vw;
            height: 100vh;
            background: white;
            border-radius: 0;
            box-shadow: none;
            margin: 0;
            display: flex;
            flex-direction: column;
            transition: all 2.5s ease-in-out;
        }

        .tv-container.fade-out {
            opacity: 0;
            transform: scale(0.98);
            filter: blur(1px);
        }

        .tv-container.fade-in {
            opacity: 1;
            transform: scale(1);
            filter: blur(0px);
        }



        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            border-radius: 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-height: 120px;
            display: flex;
            align-items: center;
        }

        @media (max-width: 768px) {
            .header .flex {
                flex-direction: column;
                text-align: center;
            }

            .header .flex > div:last-child {
                margin-top: 20px;
            }
        }

        .member-card {
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .member-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: #4f46e5;
        }

        .member-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
            text-align: center;
        }

        /* Aidat gösterimi olmadığında üye ismini daha büyük göster */
        .member-card.no-dues .member-name {
            font-size: 2rem;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-height: 120px;
        }

        .months-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 4px;
        }

        .month-item {
            text-align: center;
            padding: 6px 2px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .month-paid {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
        }

        .month-unpaid {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }

        .month-label {
            font-size: 0.5rem;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .month-status {
            font-size: 1rem;
            font-weight: 700;
        }



    </style>
</head>
<body>



    <div class="tv-container" id="tvContainer">
        <!-- Header -->
        <div class="header">
            <div class="flex items-center justify-between">
                <!-- Sol köşe - Boş -->
                <div class="flex items-center space-x-4">
                    <!-- Yıl seçici kaldırıldı - ayarlardan yönetiliyor -->
                </div>

                <!-- Orta - Başlık -->
                <div class="text-center flex-1" style="min-width: 400px;">
                    @if($settings->show_dues)
                        <h1 class="text-4xl font-bold mb-2">
                            {{ $year }} Yılı Aidat Takip Sistemi
                        </h1>
                    @else
                        <h1 class="text-4xl font-bold mb-2">
                            <i class="fas fa-heart mr-3 text-red-300"></i>
                            Rabbimiz Derneğimize Destek Veren Tüm Üyelerimizden Razı Olsun
                        </h1>
                    @endif
                </div>

            </div>
        </div>

        @if($showInfoScreen)
            <!-- Header'ı gizle -->
            <style>
                .header { display: none !important; }
            </style>

            <!-- Bilgilendirme Ekranı - Ultra Kurumsal Tasarım -->
            <div class="fixed inset-0 bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900 z-50 overflow-hidden">
                   <!-- Normal Mod: Tek Mesaj Gösterimi -->
                   @if($displayMessages->count() > 0)
                       @foreach($displayMessages as $message)
                           <div class="h-screen w-screen relative flex items-center justify-center p-16">
                        <!-- Kurumsal Arka Plan Pattern -->
                        <div class="absolute inset-0 opacity-5">
                            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, #ffffff 2px, transparent 2px), radial-gradient(circle at 75% 75%, #ffffff 2px, transparent 2px); background-size: 60px 60px;"></div>
                        </div>

                        <!-- Kurumsal Dekoratif Elementler -->
                        <div class="absolute inset-0 overflow-hidden">
                            <!-- Sol üst köşe -->
                            <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-blue-600/10 to-transparent rounded-full blur-3xl"></div>
                            <!-- Sağ alt köşe -->
                            <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tl from-indigo-600/10 to-transparent rounded-full blur-3xl"></div>
                            <!-- Merkez vurgu -->
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-gradient-to-r from-slate-700/20 to-gray-600/20 rounded-full blur-2xl"></div>
                        </div>

                        <!-- Ana İçerik Container -->
                        <div class="relative z-10 max-w-8xl w-full">
                            <div class="grid grid-cols-12 gap-16 items-center">
                                <!-- Sol Taraf - Kurumsal Resim -->
                                @if($message->image)
                                <div class="col-span-5">
                                    <div class="relative group">
                                        <!-- Ana Resim Container -->
                                        <div class="relative overflow-hidden rounded-3xl shadow-2xl border-2 border-white/20 bg-gradient-to-br from-gray-50 to-gray-100 backdrop-blur-sm">
                                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-3xl"></div>
                                            <img src="{{ asset($message->image) }}" alt="Kurumsal Mesaj"
                                                 class="relative w-full h-[500px] object-contain transform group-hover:scale-105 transition-all duration-1000 rounded-3xl p-4">

                                            <!-- Resim Üzeri Kurumsal Overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>

                                            <!-- Kurumsal Kenar Efekti -->
                                            <div class="absolute inset-0 border-2 border-white/10 rounded-2xl"></div>
                                        </div>

                                        <!-- Kurumsal Dekoratif Elementler -->
                                        <div class="absolute -top-4 -right-4 w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full shadow-xl border-2 border-white/30 animate-pulse"></div>
                                        <div class="absolute -bottom-4 -left-4 w-6 h-6 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full shadow-xl border-2 border-white/30 animate-pulse"></div>
                                        <div class="absolute top-1/2 -right-2 w-4 h-4 bg-gradient-to-br from-green-400 to-blue-500 rounded-full shadow-lg border border-white/40"></div>

                                        <!-- Kurumsal Çizgiler -->
                                        <div class="absolute top-6 left-6 w-20 h-0.5 bg-gradient-to-r from-blue-400 via-purple-400 to-transparent rounded-full"></div>
                                        <div class="absolute bottom-6 right-6 w-20 h-0.5 bg-gradient-to-l from-indigo-400 via-pink-400 to-transparent rounded-full"></div>
                                        <div class="absolute top-1/2 left-6 w-12 h-0.5 bg-gradient-to-r from-green-400 to-transparent rounded-full transform -rotate-45"></div>
                                    </div>
                                </div>
                                @endif

                                <!-- Sağ Taraf - Ultra Kurumsal İçerik -->
                                <div class="col-span-7">
                                    <div class="space-y-12">
                                        <!-- Kurumsal Başlık Bölümü -->
                                        <div class="space-y-6">
                                            <!-- Başlık - Otomatik Boyut Ayarlama -->
                                            @php
                                                $titleLength = strlen($message->title);
                                                $titleFontSize = 'text-7xl';

                                                if ($titleLength > 20) {
                                                    $titleFontSize = 'text-6xl';
                                                }
                                                if ($titleLength > 40) {
                                                    $titleFontSize = 'text-5xl';
                                                }
                                                if ($titleLength > 60) {
                                                    $titleFontSize = 'text-4xl';
                                                }
                                                if ($titleLength > 80) {
                                                    $titleFontSize = 'text-3xl';
                                                }
                                                if ($titleLength > 100) {
                                                    $titleFontSize = 'text-2xl';
                                                }
                                            @endphp

                                            <h1 class="{{ $titleFontSize }} font-black text-white leading-tight tracking-tight">
                                                {{ Str::limit($message->title, 80) }}
                                            </h1>

                                            <!-- Kurumsal Alt Çizgi -->
                                            <div class="flex items-center space-x-4">
                                                <div class="w-32 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full"></div>
                                                <div class="w-4 h-4 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full"></div>
                                                <div class="w-16 h-1 bg-gradient-to-r from-indigo-500 to-transparent rounded-full"></div>
                                            </div>
                                        </div>

                                        @if($message->content)
                                        <!-- Ultra Kurumsal İçerik Kartı -->
                                        <div class="bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-2xl rounded-3xl p-12 shadow-2xl border border-white/10 relative overflow-hidden">
                                            <!-- Kart İçi Dekorasyon -->
                                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-full blur-2xl"></div>
                                            <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-indigo-500/10 to-transparent rounded-full blur-xl"></div>

                                            <div class="relative z-10 space-y-8">
                                                <!-- İçerik Metni - Otomatik Boyut Ayarlama -->
                                                @php
                                                    $contentLength = strlen($message->content);
                                                    $fontSize = 'text-3xl';
                                                    $lineHeight = 'leading-relaxed';

                                                    if ($contentLength > 200) {
                                                        $fontSize = 'text-2xl';
                                                        $lineHeight = 'leading-normal';
                                                    }
                                                    if ($contentLength > 400) {
                                                        $fontSize = 'text-xl';
                                                        $lineHeight = 'leading-normal';
                                                    }
                                                    if ($contentLength > 600) {
                                                        $fontSize = 'text-lg';
                                                        $lineHeight = 'leading-tight';
                                                    }
                                                    if ($contentLength > 800) {
                                                        $fontSize = 'text-base';
                                                        $lineHeight = 'leading-tight';
                                                    }
                                                @endphp

                                                <p class="{{ $fontSize }} {{ $lineHeight }} text-white/95 font-light">
                                                    {{ Str::limit($message->content, 300) }}
                                                </p>

                                                <!-- Kurumsal Ayırıcı -->
                                                <div class="flex items-center space-x-6">
                                                    <div class="flex-1 h-px bg-gradient-to-r from-white/20 to-transparent"></div>
                                                    <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full shadow-lg"></div>
                                                    <div class="w-4 h-4 bg-gradient-to-br from-indigo-400 to-blue-500 rounded-full shadow-lg"></div>
                                                    <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full shadow-lg"></div>
                                                    <div class="flex-1 h-px bg-gradient-to-l from-white/20 to-transparent"></div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Ultra Kurumsal Alt Yazı -->
                                        <div class="text-center">
                                            <div class="inline-flex items-center space-x-6 bg-gradient-to-r from-white/10 to-white/5 backdrop-blur-xl rounded-3xl px-12 py-6 border border-white/20 shadow-2xl">
                                                <!-- Sol Dekorasyon -->
                                                <div class="flex space-x-2">
                                                    <div class="w-3 h-3 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full"></div>
                                                    <div class="w-2 h-2 bg-gradient-to-br from-indigo-400 to-blue-500 rounded-full"></div>
                                                </div>

                                                <!-- Alt Yazı -->
                                                <p class="text-3xl font-bold text-white tracking-wide">
                                                    {{ $message->footer_text }}
                                                </p>

                                                <!-- Sağ Dekorasyon -->
                                                <div class="flex space-x-2">
                                                    <div class="w-2 h-2 bg-gradient-to-br from-indigo-400 to-blue-500 rounded-full"></div>
                                                    <div class="w-3 h-3 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ultra Kurumsal Alt Dekorasyon -->
                        <div class="absolute bottom-0 left-0 right-0 h-3 bg-gradient-to-r from-slate-700 via-blue-600 to-slate-700"></div>

                        <!-- Kurumsal Köşe Dekorasyonları -->
                        <div class="absolute top-8 left-8 w-16 h-16 border-l-4 border-t-4 border-blue-400/50"></div>
                        <div class="absolute top-8 right-8 w-16 h-16 border-r-4 border-t-4 border-indigo-400/50"></div>
                        <div class="absolute bottom-8 left-8 w-16 h-16 border-l-4 border-b-4 border-blue-400/50"></div>
                        <div class="absolute bottom-8 right-8 w-16 h-16 border-r-4 border-b-4 border-indigo-400/50"></div>
                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Normal Mod: Tek Mesaj Göster -->
                    @foreach($displayMessages as $index => $message)
                        <div class="h-screen w-screen relative flex items-center justify-center p-16">
                            <!-- Mesaj içeriği burada olacak -->
                        </div>
                    @endforeach
                @endif
            </div>
        @else
            <!-- Üye Listesi - Dinamik Grid -->
            <div class="flex-1 p-4 overflow-hidden" style="margin-top: 120px;">
                @php
                    $keys = array_keys($memberPayments);
                    $displayLimit = $settings->member_display_limit;
                    $gridCols = $displayLimit <= 16 ? 4 : ($displayLimit <= 25 ? 5 : 6);
                    $gridRows = ceil($displayLimit / $gridCols);
                @endphp
                <div class="grid gap-4 h-full" style="grid-template-columns: repeat({{ $gridCols }}, 1fr); grid-template-rows: repeat({{ $gridRows }}, 1fr);">
                @for($i = 0; $i < $displayLimit; $i++)
                    @if(isset($keys[$i]))
                        @php $data = $memberPayments[$keys[$i]]; @endphp
                        <div class="member-card {{ !$settings->show_dues ? 'no-dues' : '' }}">
                            <div class="member-name">
                                {{ $data['member']->surname }}, {{ $data['member']->name }}
                            </div>

                            @if($settings->show_dues)
                            <div class="months-grid">
                                @foreach($months as $monthNum => $monthName)
                                    <div class="month-item {{ $data['payments'][$monthNum] ? 'month-paid' : 'month-unpaid' }}">
                                        <div class="month-label">{{ $monthName }}</div>
                                        <div class="month-status">
                                            {{ $data['payments'][$monthNum] ? '✓' : '○' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="member-card opacity-0">
                            <!-- Boş kart - 16 üye tamamlamak için -->
                        </div>
                    @endif
                @endfor
                </div>
            </div>
        @endif

    </div>

    <script>
        // İstatistikleri göster (artık gerekli değil)
        function calculateStats() {
            // Sağ köşe kaldırıldı, bu fonksiyon artık boş
        }


        // Yıl seçici kaldırıldı - ayarlardan yönetiliyor

        // Yumuşak sayfa geçişi fonksiyonu
        function smoothPageTransition(url) {
            const container = document.getElementById('tvContainer');

            // Yavaşça solma efekti - çok yumuşak
            container.classList.add('fade-out');

            setTimeout(() => {
                // Sayfayı yükle - solma tamamlandıktan sonra
                window.location.href = url;
            }, 2000); // 2 saniye yumuşak geçiş
        }

        // Otomatik sayfa geçişi (ayarlardan alınan süre)
        let pageTimer = {{ $settings->page_transition_speed }};

        const pageInterval = setInterval(() => {
            pageTimer--;

            if (pageTimer <= 0) {
                @if($hasNextPage)
                    smoothPageTransition('?year={{ $year }}&page={{ $currentPage + 1 }}');
                @else
                    smoothPageTransition('?year={{ $year }}&page=1');
                @endif
            }
        }, 1000);

        // Otomatik yenileme (ayarlardan alınan süre)
        @if($settings->auto_refresh_enabled)
        const refreshInterval = setInterval(() => {
            smoothPageTransition(window.location.href);
        }, {{ $settings->auto_refresh_interval * 1000 }});
        @endif

        // Sayfa yüklendiğinde istatistikleri hesapla
        document.addEventListener('DOMContentLoaded', function() {
            // Header'ın görünür olduğundan emin ol
            const header = document.querySelector('.header');
            if (header) {
                header.style.display = 'block';
                header.style.position = 'fixed';
                header.style.top = '0';
                header.style.left = '0';
                header.style.right = '0';
                header.style.zIndex = '1000';
            }

            // İstatistikleri hesapla
            calculateStats();
        });

        // Font Awesome ikonları
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js';
        document.head.appendChild(script);
    </script>
</body>
</html>
