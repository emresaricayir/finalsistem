<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Vefa Fotoğrafları - {{ \App\Models\Settings::get('organization_name', 'Topluluk') }}</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Noto Sans TR', 'Inter', sans-serif;
            overflow: hidden;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
        }

        .vefa-slide {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .vefa-slide.active {
            opacity: 1;
        }

        .memorial-frame {
            position: relative;
            background: rgba(0, 0, 0, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
            width: 900px;
            height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .memorial-content {
            display: flex;
            align-items: center;
            gap: 50px;
            width: 100%;
            height: 100%;
        }

        .memorial-photo-section {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .memorial-image-frame {
            position: relative;
            width: 200px;
            height: 250px;
            background: white;
            border-radius: 15px;
            padding: 8px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.8);
            border: 3px solid rgba(212, 175, 55, 0.3);
        }

        .memorial-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top;
            border-radius: 10px;
        }

        .memorial-text-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
        }

        .memorial-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: left;
            margin: 0 0 15px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            line-height: 1.2;
        }

        .memorial-subtitle {
            color: #d4af37;
            font-size: 1.3rem;
            text-align: left;
            margin: 0 0 20px 0;
            font-weight: 500;
        }

        .memorial-description {
            color: #cccccc;
            font-size: 1.1rem;
            text-align: left;
            margin: 0 0 30px 0;
            line-height: 1.6;
            font-style: italic;
        }

        .memorial-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 25px 0;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            border: 1px solid rgba(212, 175, 55, 0.2);
        }

        .memorial-info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .memorial-info-label {
            color: #d4af37;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .memorial-info-value {
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 500;
            margin-left: 20px;
        }

        .arabic-prayer {
            color: #d4af37;
            font-size: 2.2rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
            font-family: 'Amiri', 'Times New Roman', serif;
            position: absolute;
            top: -80px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            z-index: 9999;
        }

        .turkish-prayer {
            color: #e0e0e0;
            font-size: 1rem;
            text-align: center;
            margin-top: 15px;
            font-style: italic;
            line-height: 1.4;
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }

        .management-signature {
            color: #b8b8b8;
            font-size: 0.85rem;
            text-align: center;
            margin-top: 8px;
            font-weight: 500;
            position: absolute;
            bottom: -75px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .memorial-frame {
                width: 90%;
                height: auto;
                min-height: 500px;
                padding: 30px;
            }

            .memorial-content {
                flex-direction: column;
                gap: 30px;
            }

            .memorial-text-section {
                text-align: center;
            }

            .memorial-title {
                text-align: center;
                font-size: 2rem;
            }

            .memorial-info-grid {
                grid-template-columns: 1fr 1fr; /* Tablet'te de 2 sütun */
                gap: 15px;
                padding: 15px;
            }

            .memorial-info-value {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .memorial-frame {
                width: 95%;
                height: auto;
                min-height: 400px;
                padding: 15px;
                margin: 10px;
            }

            .memorial-content {
                flex-direction: column;
                gap: 20px;
            }

            .memorial-photo-section {
                align-items: center;
            }

            .memorial-image-frame {
                width: 120px;
                height: 150px;
            }

            .memorial-title {
                font-size: 1.5rem;
                text-align: center;
                margin-bottom: 15px;
            }

            .memorial-info-grid {
                padding: 12px;
                gap: 12px;
                margin: 15px 0;
                grid-template-columns: 1fr 1fr; /* 2 sütun korunuyor */
            }

            .memorial-info-label {
                font-size: 0.75rem;
            }

            .memorial-info-value {
                font-size: 0.9rem;
            }

            .arabic-prayer {
                font-size: 1.8rem;
                top: -60px;
            }

            .turkish-prayer {
                font-size: 0.9rem;
                bottom: -40px;
            }

            .management-signature {
                font-size: 0.75rem;
                bottom: -60px;
            }
        }

        @media (max-width: 480px) {
            .memorial-frame {
                width: 98%;
                padding: 10px;
                margin: 5px;
                min-height: 350px;
            }

            .memorial-image-frame {
                width: 100px;
                height: 125px;
            }

            .memorial-title {
                font-size: 1.3rem;
                margin-bottom: 10px;
            }

            .memorial-info-grid {
                padding: 8px;
                gap: 8px;
                margin: 10px 0;
                grid-template-columns: 1fr 1fr; /* Küçük mobilde de 2 sütun */
            }

            .memorial-info-label {
                font-size: 0.7rem;
            }

            .memorial-info-value {
                font-size: 0.8rem;
            }

            .arabic-prayer {
                font-size: 1.5rem;
                top: -50px;
            }

            .turkish-prayer {
                font-size: 0.8rem;
                bottom: -35px;
            }

            .management-signature {
                font-size: 0.7rem;
                bottom: -50px;
            }
        }

        /* Header Styles */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Mobilde header'ı gizle */
        @media (max-width: 768px) {
            .header {
                display: none;
            }
        }

        .header-left h1 {
            color: #d4af37;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            font-family: 'Noto Sans TR', sans-serif;
        }

        .header-left p {
            color: #b8b8b8;
            font-size: 1rem;
            margin: 5px 0 0 0;
            font-family: 'Noto Sans TR', sans-serif;
        }

        .header-right {
            text-align: right;
        }

        .current-time {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
            font-family: 'Noto Sans TR', sans-serif;
        }

        .current-date {
            color: #b8b8b8;
            font-size: 0.9rem;
            margin: 5px 0 0 0;
            font-family: 'Noto Sans TR', sans-serif;
        }

        .progress-bar {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 400px;
            height: 6px;
            background: rgba(212, 175, 55, 0.2);
            border-radius: 3px;
            overflow: hidden;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        /* Mobilde progress bar'ı küçült */
        @media (max-width: 768px) {
            .progress-bar {
                width: 80%;
                bottom: 20px;
                height: 4px;
            }
        }

        @media (max-width: 480px) {
            .progress-bar {
                width: 90%;
                bottom: 15px;
                height: 3px;
            }
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #d4af37, #ffd700);
            width: 0%;
            transition: width 0.1s linear;
            border-radius: 3px;
        }

        .memorial-prayer {
            position: fixed;
            top: 20px;
            left: 20px;
            color: #d4af37;
            font-size: 1rem;
            text-align: center;
            opacity: 0.8;
        }

        .memorial-prayer::before {
            content: '🤲';
            display: block;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        /* Boş durum mesajı için özel stil */
        .no-photos-frame {
            width: 600px;
            height: 400px;
        }

        @media (max-width: 768px) {
            .no-photos-frame {
                width: 95%;
                height: auto;
                min-height: 350px;
                padding: 15px;
            }
        }

        @media (max-width: 480px) {
            .no-photos-frame {
                width: 98%;
                min-height: 300px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <h1>{{ $organizationName }}</h1>
            <p>Vefa Sayfası</p>
        </div>
        <div class="header-right">
            <div class="current-time" id="current-time"></div>
            <div class="current-date" id="current-date"></div>
        </div>
    </div>

    @if($vefas->count() > 0)

        <!-- Photos Container -->
        <div class="relative h-screen">
            @foreach($vefas as $index => $vefa)
            <div class="vefa-slide {{ $index === 0 ? 'active' : '' }}"
                 data-vefa-id="{{ $vefa->id }}"
                 data-duration="{{ $vefa->display_duration * 1000 }}">

                <div class="memorial-frame">
                    <!-- Arabic Prayer (Outside frame - top) -->
                    <div class="arabic-prayer">إِنَّا لِلَّهِ وَإِنَّا إِلَيْهِ رَاجِعُونَ</div>

                    <div class="memorial-content">
                        <!-- Sol Taraf - Vesikalık Fotoğraf -->
                        <div class="memorial-photo-section">
                            <div class="memorial-image-frame">
                                @if($vefa->image_url)
                                    <img src="{{ $vefa->image_url }}"
                                         alt="{{ $vefa->image_alt ?? $vefa->title }}"
                                         class="memorial-image">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-lg">
                                        <div class="text-center text-gray-500">
                                            <i class="fas fa-image text-3xl mb-2"></i>
                                            <p class="text-sm">Fotoğraf Bulunamadı</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Sağ Taraf - Metin İçeriği -->
                        <div class="memorial-text-section">
                            <!-- İsim -->
                            <h2 class="memorial-title">{{ $vefa->title }}</h2>

                            <!-- Kişisel Bilgiler -->
                            @if($vefa->birth_date || $vefa->death_date || $vefa->hometown || $vefa->burial_place)
                                <div class="memorial-info-grid">
                                    @if($vefa->birth_date)
                                        <div class="memorial-info-item">
                                            <div class="memorial-info-label">
                                                <i class="fas fa-birthday-cake mr-2"></i>
                                                Doğum Tarihi
                                            </div>
                                            <div class="memorial-info-value">{{ $vefa->birth_date->format('d.m.Y') }}</div>
                                        </div>
                                    @endif

                                    @if($vefa->death_date)
                                        <div class="memorial-info-item">
                                            <div class="memorial-info-label">
                                                <i class="fas fa-star-and-crescent mr-2"></i>
                                                Vefat Tarihi
                                            </div>
                                            <div class="memorial-info-value">{{ $vefa->death_date->format('d.m.Y') }}</div>
                                        </div>
                                    @endif

                                    @if($vefa->hometown)
                                        <div class="memorial-info-item">
                                            <div class="memorial-info-label">
                                                <i class="fas fa-map-marker-alt mr-2"></i>
                                                Memleketi
                                            </div>
                                            <div class="memorial-info-value">{{ $vefa->hometown }}</div>
                                        </div>
                                    @endif

                                    @if($vefa->burial_place)
                                        <div class="memorial-info-item">
                                            <div class="memorial-info-label">
                                                <i class="fas fa-monument mr-2"></i>
                                                Defin Yeri
                                            </div>
                                            <div class="memorial-info-value">{{ $vefa->burial_place }}</div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Turkish Prayer (Outside frame - bottom) -->
                    <div class="turkish-prayer">Rabbimiz geçmişlerimize rahmetiyle muamele eylesin</div>

                    <!-- Management Signature (Outside frame - bottom) -->
                    <div class="management-signature">DERNEK YÖNETİM KURULU</div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-fill" id="progress-fill"></div>
        </div>
    @else
        <!-- No Photos Message -->
        <div class="h-screen flex items-center justify-center">
            <div class="text-center">
                <div class="memorial-frame no-photos-frame">
                    <div class="memorial-content">
                        <div class="memorial-photo-section">
                            <div class="memorial-image-frame">
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded-lg">
                                    <div class="text-center text-gray-500">
                                        <i class="fas fa-heart text-4xl mb-2"></i>
                                        <p class="text-sm">Vefa Fotoğrafı</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="memorial-text-section">
                            <h2 class="memorial-title">Henüz Vefa Fotoğrafı Yok</h2>
                            <p class="memorial-description">Yakında vefat eden üyelerimizin anısını yaşatmak için vefa fotoğrafları burada görüntülenecek</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        class VefaSlider {
            constructor() {
                this.vefas = document.querySelectorAll('.vefa-slide');
                this.currentIndex = 0;
                this.isTransitioning = false;

                if (this.vefas.length === 0) return;

                this.startSlider();
            }

            startSlider() {
                this.showVefa(0);
            }

            showVefa(index) {
                if (this.isTransitioning || index >= this.vefas.length) return;

                this.isTransitioning = true;

                // Hide current slide
                const currentSlide = this.vefas[this.currentIndex];
                if (currentSlide) {
                    currentSlide.classList.remove('active');
                }

                // Show new slide
                setTimeout(() => {
                    this.currentIndex = index;
                    const newSlide = this.vefas[this.currentIndex];

                    if (newSlide) {
                        newSlide.classList.add('active');
                        this.startProgressBar(newSlide);
                    }

                    this.isTransitioning = false;
                }, 500);
            }

            startProgressBar(vefaElement) {
                const progressFill = document.getElementById('progress-fill');
                if (!progressFill) return;

                const duration = parseInt(vefaElement.dataset.duration) || 5000;
                const startTime = Date.now();

                const updateProgress = () => {
                    const elapsed = Date.now() - startTime;
                    const progress = Math.min((elapsed / duration) * 100, 100);

                    progressFill.style.width = progress + '%';

                    if (progress < 100) {
                        requestAnimationFrame(updateProgress);
                    } else {
                        // Move to next photo
                        this.nextVefa();
                    }
                };

                requestAnimationFrame(updateProgress);
            }

            nextVefa() {
                const nextIndex = (this.currentIndex + 1) % this.vefas.length;
                this.showVefa(nextIndex);
            }
        }

        // Update time function
        function updateTime() {
            const now = new Date();
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const dateOptions = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            };

            const timeString = now.toLocaleTimeString('tr-TR', timeOptions);
            const dateString = now.toLocaleDateString('tr-TR', dateOptions);

            document.getElementById('current-time').textContent = timeString;
            document.getElementById('current-date').textContent = dateString;
        }

        // Refresh Vefa data
        function refreshVefas() {
            fetch('{{ route("vefas.api") }}')
                .then(response => response.json())
                .then(data => {
                    // Check if data has changed
                    const currentIds = Array.from(document.querySelectorAll('.vefa-slide')).map(slide =>
                        parseInt(slide.getAttribute('data-vefa-id'))
                    ).sort();

                    const newIds = data.map(vefa => vefa.id).sort();

                    // If IDs are different, reload the page
                    if (JSON.stringify(currentIds) !== JSON.stringify(newIds)) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error refreshing vefas:', error);
                });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const slider = new VefaSlider();

            // Update time immediately and then every second
            updateTime();
            setInterval(updateTime, 1000);

            // Refresh vefas every 10 seconds
            setInterval(refreshVefas, 10000);

            // Refresh when tab becomes visible
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    refreshVefas();
                }
            });
        });
    </script>
</body>
</html>
