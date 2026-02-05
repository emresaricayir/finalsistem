<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Topluluk Etkinlikleri - Dijital Ekran</title>

    <!-- Favicon -->
    @if(\App\Models\Settings::hasFavicon())
        <link rel="icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
        <link rel="shortcut icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
    @endif

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TR:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Noto Sans TR', 'Inter', sans-serif;
            overflow: hidden;
        }

        .event-slide {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .event-slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .event-slide.exit {
            opacity: 0;
            transform: scale(1.1);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #000000 0%, #0f0c29 15%, #1e1b4b 30%, #312e81 45%, #4338ca 60%, #6366f1 75%, #818cf8 90%, #a5b4fc 100%);
        }

        .glass-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse at top, rgba(30, 27, 75, 0.25) 0%, transparent 60%),
                        radial-gradient(ellipse at bottom, rgba(49, 46, 129, 0.22) 0%, transparent 60%),
                        linear-gradient(135deg, rgba(67, 56, 202, 0.18) 0%, transparent 50%);
            backdrop-filter: blur(10px);
            z-index: -1;
        }

        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(30, 27, 75, 0.15) 50%, transparent 70%);
            animation: shimmer 30s ease-in-out infinite;
            z-index: -2;
        }

        @keyframes shimmer {
            0%, 100% { transform: translateX(-100%) rotate(45deg); }
            50% { transform: translateX(100%) rotate(45deg); }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-up {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .glass-effect {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .premium-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .gold-accent {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
        }

        .elegant-text {
            font-family: 'Playfair Display', 'Noto Sans TR', serif;
            font-weight: 300;
            letter-spacing: 0.5px;
        }

        .vibes-title {
            font-family: "Great Vibes", cursive;
            font-weight: 400;
            letter-spacing: 1px;
            line-height: 1.1;
        }

        .wedding-title {
            font-family: "Great Vibes", cursive;
            font-weight: 400;
            letter-spacing: 1px;
            line-height: 1.1;
        }

        .default-title {
            font-family: 'Noto Sans TR', 'Inter', sans-serif;
            font-weight: 700;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .noto-text {
            font-family: 'Noto Sans TR', 'Inter', sans-serif;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* Advertisement specific styles */
        .ad-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        .ad-image-side {
            position: relative;
            overflow: hidden;
        }

        .ad-content-side {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .header {
                display: none;
            }

            .main-content {
                padding-top: 1rem;
                padding-bottom: 1rem;
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .event-slide {
                padding: 1rem;
            }

            .event-title {
                font-size: 2.5rem !important;
                line-height: 1.1;
                margin-bottom: 1.5rem;
            }

            .event-badge {
                top: 0.5rem;
                right: 0.5rem;
                padding: 0.5rem 1rem;
            }

            .event-badge span {
                font-size: 0.875rem;
            }

            .event-date-time {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .event-date-time .flex {
                flex-direction: column;
                gap: 1rem;
            }

            .event-date-time .w-px {
                display: none;
            }

            .event-date-time .text-6xl {
                font-size: 2.5rem;
            }

            .event-date-time .text-xl {
                font-size: 1rem;
            }

            .event-location {
                padding: 0.75rem 1rem;
                margin-bottom: 1rem;
            }

            .event-location span {
                font-size: 1.125rem;
            }

            .event-description {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }

            .event-description p {
                font-size: 1.125rem;
                line-height: 1.6;
            }

            .progress-bar {
                margin-top: 1rem;
            }

            .no-events-card {
                padding: 2rem;
            }

            .no-events-title {
                font-size: 2.5rem;
            }

            .no-events-text {
                font-size: 1.25rem;
            }

            /* Advertisement mobile styles */
            .ad-grid {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .ad-image-side {
                min-height: 300px;
                order: 1;
            }

            .ad-content-side {
                padding: 2rem;
                order: 2;
            }

            .ad-content-side h2 {
                font-size: 2.5rem !important;
            }

            .ad-content-side p {
                font-size: 1.125rem !important;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 0.5rem;
            }

            .event-slide {
                padding: 0.5rem;
            }

            .event-title {
                font-size: 2rem !important;
                margin-bottom: 1rem;
            }

            .event-badge {
                top: 0.25rem;
                right: 0.25rem;
                padding: 0.375rem 0.75rem;
            }

            .event-badge span {
                font-size: 0.75rem;
            }

            .event-date-time {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .event-date-time .text-6xl {
                font-size: 2rem;
            }

            .event-date-time .text-xl {
                font-size: 0.875rem;
            }

            .event-location {
                padding: 0.5rem 0.75rem;
                margin-bottom: 0.75rem;
            }

            .event-location span {
                font-size: 1rem;
            }

            .event-description {
                padding: 1rem;
                margin-bottom: 0.75rem;
            }

            .event-description p {
                font-size: 1rem;
                line-height: 1.5;
            }

            .no-events-card {
                padding: 1.5rem;
            }

            .no-events-title {
                font-size: 2rem;
            }

            .no-events-text {
                font-size: 1.125rem;
            }
        }
    </style>
</head>
<body class="h-full gradient-bg">
    <!-- Animated Background -->
    <div class="animated-bg"></div>
    <!-- Glass Overlay -->
    <div class="glass-overlay"></div>
    <!-- Header -->
    <div class="header fixed top-0 left-0 right-0 z-50 glass-effect border-b border-white border-opacity-30">
        <div class="px-12 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    @if(\App\Models\Settings::hasLogo())
                        <div class="w-16 h-16 bg-white bg-opacity-10 rounded-2xl flex items-center justify-center border border-white border-opacity-20">
                            <img src="{{ \App\Models\Settings::getLogoUrl() }}"
                                 alt="{{ \App\Models\Settings::get('organization_name', 'Topluluk') }}"
                                 class="w-12 h-12 object-contain">
                        </div>
                    @else
                        <div class="w-16 h-16 bg-white bg-opacity-10 rounded-2xl flex items-center justify-center border border-white border-opacity-20">
                            <i class="fas fa-mosque text-3xl text-white text-opacity-80"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-3xl font-bold text-white text-shadow noto-text">
                            {{ \App\Models\Settings::get('organization_name', 'Topluluk') }}
                        </h1>
                    </div>
                </div>
                <div class="text-white text-opacity-90">
                    <div class="text-right">
                        <div id="current-time" class="text-4xl font-bold text-shadow noto-text"></div>
                        <div id="current-date" class="text-xl noto-text"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content h-full pt-24 pb-8 px-8">
        @if(count($mixedContent) > 0)
            <!-- Mixed Content Container -->
            <div id="mixed-container" class="h-full relative">
                @foreach($mixedContent as $index => $item)
                    @if($item['type'] === 'event')
                        <!-- Event Slide -->
                        <div class="event-slide {{ $index === 0 ? 'active' : '' }}"
                             data-event-id="{{ $item['data']->id }}"
                             data-duration="{{ $item['duration'] }}"
                             data-event-type="{{ $item['data']->event_type }}"
                             data-type="event">
                            <div class="text-center w-full h-full flex flex-col justify-center items-center px-12">
                                <!-- Event Type Badge (Top Right) -->
                                <div class="event-badge absolute top-8 right-8 slide-up">
                                    <div class="premium-card rounded-2xl px-6 py-3">
                                        <span class="text-white text-lg font-semibold elegant-text">{{ $item['data']->event_type_label }}</span>
                                    </div>
                                </div>

                                <!-- Event Title -->
                                <div class="mb-12 slide-up">
                                    <h2 class="text-6xl lg:text-7xl font-black text-white text-shadow mb-6 leading-tight event-title" data-event-type="{{ $item['data']->event_type }}">
                                        {{ $item['data']->title }}
                                    </h2>
                                </div>

                                <!-- Event Date & Time -->
                                <div class="mb-12 slide-up">
                                    <div class="event-date-time premium-card rounded-3xl p-8 max-w-2xl">
                                        <div class="flex items-center justify-center space-x-8">
                                            <div class="text-center">
                                                <div class="text-4xl lg:text-5xl font-bold text-white text-shadow mb-2 noto-text">
                                                    {{ $item['data']->event_date->format('d.m.Y') }}
                                                </div>
                                                <div class="text-lg font-medium text-white text-opacity-80 noto-text">
                                                    Tarih
                                                </div>
                                            </div>
                                            <div class="w-px h-16 bg-white bg-opacity-30"></div>
                                            <div class="text-center">
                                                <div class="text-4xl lg:text-5xl font-bold text-white text-shadow mb-2 noto-text">
                                                    {{ $item['data']->event_date->format('H:i') }}
                                                </div>
                                                <div class="text-lg font-medium text-white text-opacity-80 noto-text">
                                                    Saat
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Event Location -->
                                @if($item['data']->location)
                                <div class="mb-8 slide-up">
                                    <div class="event-location premium-card rounded-2xl px-6 py-4 inline-block">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-2 h-2 bg-white bg-opacity-60 rounded-full"></div>
                                            <span class="text-white text-xl font-semibold elegant-text">{{ $item['data']->location }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Event Description -->
                                @if($item['data']->description)
                                <div class="mb-8 slide-up max-w-4xl">
                                    <div class="event-description premium-card rounded-3xl p-8">
                                        <p class="text-white text-lg lg:text-xl leading-relaxed text-opacity-90 elegant-text">
                                            {{ $item['data']->description }}
                                        </p>
                                    </div>
                                </div>
                                @endif

                                <!-- Progress Bar -->
                                <div class="progress-bar slide-up w-full max-w-4xl">
                                    <div class="w-full bg-white bg-opacity-10 rounded-full h-3 border border-white border-opacity-20">
                                        <div class="event-progress gold-accent rounded-full h-3 transition-all duration-100 ease-linear"
                                             style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Advertisement Slide -->
                        <div class="event-slide {{ $index === 0 ? 'active' : '' }}"
                             data-ad-id="{{ $item['data']->id }}"
                             data-duration="{{ $item['duration'] }}"
                             data-type="advertisement">
                            <div class="text-center w-full h-full flex flex-col justify-center items-center px-12">
                                <!-- Advertisement Content - Split Layout -->
                                <div class="w-full max-w-7xl mx-auto slide-up">
                                    <div class="premium-card rounded-3xl overflow-hidden">
                                        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[600px]">
                                            <!-- Left Side - Image -->
                                            <div class="relative overflow-hidden bg-gradient-to-br from-purple-900/20 to-blue-900/20">
                                                @if($item['data']->image)
                                                    <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-slate-800/20 to-slate-900/20">
                                                        <img src="{{ asset($item['data']->image) }}"
                                                             alt="{{ $item['data']->title }}"
                                                             class="max-w-full max-h-full object-contain p-8">
                                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-black/20"></div>
                                                    </div>
                                                @else
                                                    <!-- Placeholder with elegant pattern -->
                                                    <div class="absolute inset-0 bg-gradient-to-br from-purple-600/30 to-blue-600/30">
                                                        <div class="absolute inset-0 opacity-20" style="background-image:
                                                            radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
                                                            radial-gradient(circle at 75% 75%, rgba(255,255,255,0.08) 0%, transparent 50%),
                                                            linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.05) 50%, transparent 60%);">
                                                        </div>
                                                        <div class="absolute inset-0 flex items-center justify-center">
                                                            <div class="w-32 h-32 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/20">
                                                                <i class="fas fa-image text-6xl text-white/60"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Decorative Elements -->
                                                <div class="absolute bottom-8 left-8">
                                                    <div class="flex space-x-2">
                                                        <div class="w-3 h-3 rounded-full bg-white/30"></div>
                                                        <div class="w-3 h-3 rounded-full bg-white/20"></div>
                                                        <div class="w-3 h-3 rounded-full bg-white/10"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Right Side - Content -->
                                            <div class="flex flex-col justify-center p-12 lg:p-16 bg-gradient-to-br from-slate-900/40 to-slate-800/40 backdrop-blur-sm">
                                                <!-- Title -->
                                                <div class="mb-6">
                                                    <h2 class="text-3xl lg:text-4xl font-black text-white text-shadow leading-tight default-title mb-3">
                                                        {{ $item['data']->title }}
                                                    </h2>
                                                    <div class="w-16 h-1 bg-gradient-to-r from-purple-400 to-blue-400 rounded-full"></div>
                                                </div>

                                                <!-- Content -->
                                                @if($item['data']->content)
                                                <div class="mb-6">
                                                    <p class="text-white/90 text-lg lg:text-xl leading-relaxed elegant-text font-light">
                                                        {{ $item['data']->content }}
                                                    </p>
                                                </div>
                                                @endif

                                                <!-- Decorative corner -->
                                                <div class="absolute top-0 right-0 w-32 h-32 opacity-10">
                                                    <div class="absolute inset-0 bg-gradient-to-bl from-white/20 to-transparent transform rotate-45 translate-x-16 -translate-y-16"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="progress-bar slide-up w-full max-w-4xl">
                                    <div class="w-full bg-white bg-opacity-10 rounded-full h-3 border border-white border-opacity-20">
                                        <div class="event-progress gold-accent rounded-full h-3 transition-all duration-100 ease-linear"
                                             style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <!-- No Content Message -->
            <div class="h-full flex items-center justify-center">
                <div class="text-center">
                    <div class="no-events-card premium-card rounded-3xl p-16 max-w-4xl">
                        <h2 class="no-events-title text-7xl font-bold text-white text-shadow mb-8 elegant-text">
                            Henüz İçerik Yok
                        </h2>
                        <div class="w-24 h-1 bg-white bg-opacity-30 rounded-full mx-auto mb-8"></div>
                        <p class="no-events-text text-3xl text-white text-opacity-80 elegant-text">
                            Etkinlikler ve reklamlar burada görüntülenecek
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Geometric Pattern -->
    <div class="fixed inset-0 -z-10 opacity-9">
        <div class="absolute inset-0" style="background-image:
            radial-gradient(circle at 25% 25%, rgba(30, 27, 75, 0.18) 0%, transparent 40%),
            radial-gradient(circle at 75% 75%, rgba(49, 46, 129, 0.18) 0%, transparent 40%),
            radial-gradient(circle at 50% 50%, rgba(67, 56, 202, 0.12) 0%, transparent 60%),
            conic-gradient(from 0deg at 50% 50%, transparent 0deg, rgba(30, 27, 75,0.08) 90deg, transparent 180deg, rgba(49, 46, 129,0.08) 270deg, transparent 360deg);"></div>
    </div>

    <!-- Hexagonal Pattern -->
    <div class="fixed inset-0 -z-10 opacity-5">
        <div class="absolute inset-0" style="background-image:
            repeating-linear-gradient(30deg, transparent, transparent 50px, rgba(30, 27, 75,0.025) 50px, rgba(30, 27, 75,0.025) 52px),
            repeating-linear-gradient(-30deg, transparent, transparent 50px, rgba(49, 46, 129,0.025) 50px, rgba(49, 46, 129,0.025) 52px),
            repeating-linear-gradient(90deg, transparent, transparent 50px, rgba(67, 56, 202,0.020) 50px, rgba(67, 56, 202,0.020) 52px);"></div>
    </div>

    <!-- Floating Particles -->
    <div class="fixed inset-0 -z-10 opacity-6">
        <div class="absolute inset-0" style="background-image:
            radial-gradient(2px 2px at 20px 30px, rgba(59, 130, 246,0.4), transparent),
            radial-gradient(2px 2px at 40px 70px, rgba(99, 102, 241,0.3), transparent),
            radial-gradient(1px 1px at 90px 40px, rgba(147, 197, 253,0.5), transparent),
            radial-gradient(1px 1px at 130px 80px, rgba(59, 130, 246,0.4), transparent),
            radial-gradient(2px 2px at 160px 30px, rgba(99, 102, 241,0.3), transparent),
            radial-gradient(1px 1px at 200px 60px, rgba(147, 197, 253,0.4), transparent),
            radial-gradient(2px 2px at 240px 90px, rgba(59, 130, 246,0.3), transparent),
            radial-gradient(1px 1px at 280px 20px, rgba(99, 102, 241,0.5), transparent),
            radial-gradient(2px 2px at 320px 50px, rgba(147, 197, 253,0.4), transparent),
            radial-gradient(1px 1px at 360px 80px, rgba(59, 130, 246,0.3), transparent);"></div>
    </div>

    <script>
        // Update time every second
        function updateTime() {
            const now = new Date();
            const timeElement = document.getElementById('current-time');
            const dateElement = document.getElementById('current-date');

            if (timeElement) {
                timeElement.textContent = now.toLocaleTimeString('de-DE', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    timeZone: 'Europe/Berlin'
                });
            }

            if (dateElement) {
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    timeZone: 'Europe/Berlin'
                };

                // Berlin saatine göre tarih al
                const berlinTime = new Date(now.toLocaleString("en-US", {timeZone: "Europe/Berlin"}));

                // Türkçe gün ve ay isimleri
                const turkishDays = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
                const turkishMonths = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];

                const dayName = turkishDays[berlinTime.getDay()];
                const monthName = turkishMonths[berlinTime.getMonth()];
                const year = berlinTime.getFullYear();
                const day = berlinTime.getDate();

                dateElement.textContent = `${dayName}, ${day} ${monthName} ${year}`;
            }
        }

        // Apply font based on event type
        function applyEventFonts() {
            const eventTitles = document.querySelectorAll('.event-title');
            eventTitles.forEach(title => {
                const eventType = title.getAttribute('data-event-type');
                if (eventType === 'wedding') {
                    title.classList.add('wedding-title');
                    title.classList.remove('default-title');
                } else {
                    title.classList.add('default-title');
                    title.classList.remove('wedding-title');
                }
            });
        }

        // Event slider functionality
        class EventSlider {
            constructor() {
                this.events = document.querySelectorAll('.event-slide');
                this.currentIndex = 0;
                this.isTransitioning = false;

                if (this.events.length === 0) return;

                this.startSlider();
                applyEventFonts(); // Apply fonts on initialization
            }

            startSlider() {
                this.showEvent(0);

            // Auto-refresh every 10 seconds for faster updates
            setInterval(() => {
                this.refreshEvents();
            }, 10000);
            }

            showEvent(index) {
                if (this.isTransitioning || this.events.length === 0) return;

                this.isTransitioning = true;

                // Hide current event
                if (this.events[this.currentIndex]) {
                    this.events[this.currentIndex].classList.remove('active');
                    this.events[this.currentIndex].classList.add('exit');
                }

                // Show new event
                this.currentIndex = index;
                const currentEvent = this.events[this.currentIndex];

                if (currentEvent) {
                    // Show event
                    setTimeout(() => {
                        currentEvent.classList.remove('exit');
                        currentEvent.classList.add('active');
                        this.startProgressBar(currentEvent);
                    }, 100);
                }

                setTimeout(() => {
                    this.isTransitioning = false;
                }, 800);
            }

            setEventBackground(eventElement) {
                // Arka plan rengi sabit kalacak - sadece etkinlik içeriği değişecek
                // Bu fonksiyon artık kullanılmıyor ama boş bırakıyoruz
            }

            startProgressBar(eventElement) {
                const progressBar = eventElement.querySelector('.event-progress');
                if (!progressBar) return;

                const duration = parseInt(eventElement.dataset.duration) || 5000;
                const startTime = Date.now();

                const updateProgress = () => {
                    const elapsed = Date.now() - startTime;
                    const progress = Math.min((elapsed / duration) * 100, 100);

                    progressBar.style.width = progress + '%';

                    if (progress < 100) {
                        requestAnimationFrame(updateProgress);
                    } else {
                        // Move to next event
                        this.nextEvent();
                    }
                };

                requestAnimationFrame(updateProgress);
            }

            nextEvent() {
                const nextIndex = (this.currentIndex + 1) % this.events.length;
                this.showEvent(nextIndex);
            }

            refreshEvents() {
                // Reload mixed content from server
                fetch('{{ route("events.api") }}')
                    .then(response => response.json())
                    .then(data => {
                        const mixedContent = data.mixedContent || [];

                        // Check if content has changed
                        const currentIds = Array.from(this.events).map(el => {
                            const type = el.dataset.type;
                            const id = type === 'event' ? el.dataset.eventId : el.dataset.adId;
                            return `${type}-${id}`;
                        });

                        const newIds = mixedContent.map(item => `${item.type}-${item.id}`);

                        // Check if count changed or any ID is different
                        const hasChanged = currentIds.length !== newIds.length ||
                                        currentIds.some(id => !newIds.includes(id)) ||
                                        newIds.some(id => !currentIds.includes(id));

                        if (hasChanged) {
                            console.log('Mixed content changed, reloading...');
                            location.reload();
                        } else {
                            // Apply fonts even if no change (in case of dynamic updates)
                            applyEventFonts();
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing mixed content:', error);
                    });
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateTime();
            setInterval(updateTime, 1000);

            const slider = new EventSlider();

            // Also refresh when page becomes visible (user switches back to tab)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    slider.refreshEvents();
                }
            });
        });

        // Handle visibility change (pause when tab is not visible)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Pause animations when tab is hidden
                document.body.style.animationPlayState = 'paused';
            } else {
                // Resume animations when tab becomes visible
                document.body.style.animationPlayState = 'running';
            }
        });

        // Prevent context menu and text selection
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('selectstart', e => e.preventDefault());

        // Fullscreen on double click
        document.addEventListener('dblclick', function() {
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen();
            }
        });
    </script>
</body>
</html>
