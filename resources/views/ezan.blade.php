@php
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
@endphp
<!DOCTYPE html>
<html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="tv-optimization" content="true">
        <meta name="tizen-optimization" content="true">
        <title>{{ $settings->association_name ?? 'Namaz Vakitleri' }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body {
                min-height: 100vh;
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                color: white;
                position: relative;
                background-color: #000000;
                overflow: hidden;
            }

            .background-container {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url('{{ asset("storage/templates/bg.jpg") }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-color: #000000;
                z-index: -1;
            }

            .header-content {
                position: relative;
                width: 100%;
                height: 100%;
            }

            .logo-section {
                position: fixed;
                left: 40px;
                top: 40px;
                display: flex;
                align-items: center;
                z-index: 1000;
            }

            .association-logo {
                margin-right: 25px;
            }

            .time-section {
                position: fixed;
                right: 40px;
                top: 20px;
                text-align: right;
                z-index: 1000;
            }
            .header-bar {
                background: rgba(52, 58, 64, 0.8);
                padding: 75px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }
            .mosque-name {
                font-size: 2.5rem;
                color: white;
                font-weight: 900;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            }
            .current-time {
                font-size: 4rem;
                font-weight: 900;
                color: #ffc107;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            }
            .prayer-times {
                display: flex;
                position: fixed;
                bottom: 60px;
                left: 0;
                right: 0;
                height: 160px;
                z-index: 999;
            }
            .prayer-box {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                text-align: center;
                padding: 20px 10px;
                border-right: 1px solid rgba(255, 255, 255, 0.3);
                background-color: rgba(0, 0, 0, 0.6);
            }
            .prayer-box:last-child {
                border-right: none;
            }
            .prayer-name {
                font-size: 2.7rem;
                color: white;
                font-weight: 900;
                margin-bottom: 10px;
            }
            .prayer-time {
                font-size: 2.7rem;
                color: #ffc107;
                font-weight: 900;
            }
            .prayer-box.active {
                border: 3px solid #FFD700;
                border-radius: 10px;
                background-color: rgba(255, 215, 0, 0.2);
            }
            .central-next-prayer {
                position: fixed;
                bottom: 230px;
                left: 50%;
                transform: translateX(-50%);
                background-color: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 8px 20px;
                border-radius: 12px;
                text-align: center;
                z-index: 998;
            }
            .central-next-prayer-label {
                font-size: 2.5rem;
                font-weight: bold;
                margin-bottom: 2px;
                margin-right: 20px;
                color: white;
            }
            .central-next-prayer-time {
                font-size: 2.5rem;
                font-weight: bold;
                color: #ffc107;
            }

            /* Sabah Namazı Bilgisi - Responsive Stiller */
            .sabah-prayer-info {
                position: fixed;
                bottom: 230px;
                left: 2%;
                text-align: center;
                font-size: 20px;
                color: #ffffff;
                font-weight: 600;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.9);
                background: rgba(0,0,0,0.6);
                padding: 8px 16px;
                border-radius: 15px;
                z-index: 100;
                white-space: nowrap;
                display: none;
            }

            /* Cuma Namazı Bilgisi - Responsive Stiller */
            .friday-prayer-info {
                position: fixed;
                bottom: 230px;
                right: 2%;
                text-align: center;
                font-size: 20px;
                color: #ffffff;
                font-weight: 600;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.9);
                background: rgba(0,0,0,0.6);
                padding: 8px 16px;
                border-radius: 15px;
                z-index: 100;
                white-space: nowrap;
                display: none;
            }

            /* Responsive Styles */
            @media (max-width: 992px) {
                .sabah-prayer-info {
                    bottom: 250px;
                    left: 1%;
                    font-size: 18px;
                    padding: 6px 12px;
                }
                .friday-prayer-info {
                    bottom: 250px;
                    right: 1%;
                    font-size: 18px;
                    padding: 6px 12px;
                }
                .central-next-prayer {
                    bottom: 210px;
                    padding: 8px 20px;
                }
                .central-next-prayer-label {
                    font-size: 2.3rem;
                }
                .central-next-prayer-time {
                    font-size: 2.3rem;
                }
            }

            @media (max-width: 768px) {
                .sabah-prayer-info {
                    bottom: 500px;
                    left: 1%;
                    font-size: 16px;
                    padding: 5px 10px;
                }
                .friday-prayer-info {
                    bottom: 460px;
                    right: 1%;
                    font-size: 16px;
                    padding: 5px 10px;
                }
                .central-next-prayer {
                    bottom: 380px;
                    padding: 8px 20px;
                    left: 50%;
                    transform: translateX(-50%);
                    width: auto;
                    max-width: 90%;
                }
                .central-next-prayer-label {
                    font-size: 1.8rem;
                    margin-right: 15px;
                }
                .central-next-prayer-time {
                    font-size: 1.8rem;
                }
                .header-bar {
                    padding: 30px 0;
                }
                .logo-section {
                    left: 10px;
                    top: 10px;
                }
                .time-section {
                    right: 10px;
                    top: 10px;
                }
                .association-logo {
                    margin-right: 10px;
                    height: 40px !important;
                    max-width: 120px !important;
                }
                .mosque-name {
                    font-size: 1.6rem;
                }
                .current-time {
                    font-size: 2.5rem;
                }
                .prayer-times {
                    height: 120px;
                }
                .prayer-box {
                    padding: 15px 8px;
                }
                .prayer-name {
                    font-size: 2.2rem;
                }
                .prayer-time {
                    font-size: 2.2rem;
                }
            }

            @media (max-width: 576px) {
                .sabah-prayer-info {
                    bottom: 280px;
                    left: 0.5%;
                    font-size: 14px;
                    padding: 4px 8px;
                }
                .friday-prayer-info {
                    bottom: 280px;
                    right: 0.5%;
                    font-size: 14px;
                    padding: 4px 8px;
                }
                .central-next-prayer {
                    bottom: 200px;
                    padding: 6px 15px;
                    max-width: 95%;
                }
                .central-next-prayer-label {
                    font-size: 1.5rem;
                    margin-right: 10px;
                }
                .central-next-prayer-time {
                    font-size: 1.5rem;
                }
                .header-bar {
                    padding: 25px 0;
                }
                .logo-section {
                    left: 10px;
                    top: 10px;
                }
                .time-section {
                    right: 10px;
                    top: 10px;
                }
                .association-logo {
                    margin-right: 10px;
                    height: 40px !important;
                    max-width: 100px !important;
                }
                .mosque-name {
                    font-size: 1.5rem;
                }
                .current-time {
                    font-size: 2.5rem;
                }
            }
        </style>
    </head>
    <body>
        <!-- Arka Plan Container -->
        <div class="background-container" id="background-container"></div>

        <!-- Üst Bar -->
        <div class="header-bar">
            <div class="container">
                <div class="header-content">
                    <div class="logo-section">
                        @if($settings->association_logo)
                            <img src="/storage/{{ ltrim($settings->association_logo, '/') }}" alt="Logo" class="association-logo" style="height: 60px; max-width: 150px;">
                        @endif
                        <h1 class="mosque-name mb-0">{{ $settings->association_name ?? 'Helmstedt Fatih Camii' }}</h1>
                    </div>
                    <div class="time-section">
                        <div class="text-white h4 mb-0" id="current-date">{{ $now->locale('tr')->translatedFormat('d F Y l') }}</div>
                        <div class="current-time" id="current-time">{{ $now->format('H:i:s') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Namaz Vakitleri -->
        @if($prayerTime)
        <div class="prayer-times">
            @php
                $getTimeString = function($timeValue) {
                    if (empty($timeValue)) {
                        return '00:00:00';
                    }
                    $timeStr = (string)$timeValue;
                    if (preg_match('/(\d{4}-\d{2}-\d{2})\s+(\d{2}:\d{2}(?::\d{2})?)/', $timeStr, $matches)) {
                        return $matches[2];
                    }
                    if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $timeStr)) {
                        if (substr_count($timeStr, ':') === 1) {
                            return $timeStr . ':00';
                        }
                        return $timeStr;
                    }
                    return $timeStr;
                };
            @endphp
            <div class="prayer-box" id="imsak">
                <div class="prayer-name">İmsak</div>
                <div class="prayer-time">{{ $prayerTime->imsak ? \Carbon\Carbon::createFromFormat('H:i:s', $getTimeString($prayerTime->imsak))->format('H:i') : '--:--' }}</div>
            </div>
            <div class="prayer-box" id="gunes">
                <div class="prayer-name">Güneş</div>
                <div class="prayer-time">{{ $prayerTime->gunes ? \Carbon\Carbon::createFromFormat('H:i:s', $getTimeString($prayerTime->gunes))->format('H:i') : '--:--' }}</div>
            </div>
            <div class="prayer-box" id="ogle">
                <div class="prayer-name">Öğle</div>
                <div class="prayer-time">{{ $prayerTime->ogle ? \Carbon\Carbon::createFromFormat('H:i:s', $getTimeString($prayerTime->ogle))->format('H:i') : '--:--' }}</div>
            </div>
            <div class="prayer-box" id="ikindi">
                <div class="prayer-name">İkindi</div>
                <div class="prayer-time">{{ $prayerTime->ikindi ? \Carbon\Carbon::createFromFormat('H:i:s', $getTimeString($prayerTime->ikindi))->format('H:i') : '--:--' }}</div>
            </div>
            <div class="prayer-box" id="aksam">
                <div class="prayer-name">Akşam</div>
                <div class="prayer-time">{{ $prayerTime->aksam ? \Carbon\Carbon::createFromFormat('H:i:s', $getTimeString($prayerTime->aksam))->format('H:i') : '--:--' }}</div>
            </div>
            <div class="prayer-box" id="yatsi">
                <div class="prayer-name">Yatsı</div>
                <div class="prayer-time">{{ $prayerTime->yatsi ? \Carbon\Carbon::createFromFormat('H:i:s', $getTimeString($prayerTime->yatsi))->format('H:i') : '--:--' }}</div>
            </div>
        </div>
        @endif

        @if($sabahPrayerTime)
        <div class="sabah-prayer-info">
            <div style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                <div style="display: flex; align-items: center; justify-content: center; width: 100%;">
                    <span>صلاة الفجر</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: center; width: 100%; margin-top: 5px;">
                    <i class="fa-solid fa-star-and-crescent me-2"></i>
                    <span>Sabah Namazı: {{ $sabahPrayerTime->format('H:i') }}</span>
                </div>
            </div>
        </div>
        @endif

        @if($fridayPrayerTime)
        <div class="friday-prayer-info">
            <div style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                <div style="display: flex; align-items: center; justify-content: center; width: 100%;">
                    <span>صلاة الجمعة</span>
                </div>
                <div style="display: flex; align-items: center; justify-content: center; width: 100%; margin-top: 5px;">
                    <i class="fa-solid fa-mosque me-2"></i>
                    <span>Cuma Namazı: {{ $fridayPrayerTime->format('H:i') }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Ortada Sonraki Vakit Gösterimi -->
        <div class="central-next-prayer" id="central-next-prayer">
            <div style="display: flex; align-items: center; justify-content: center;">
                <span class="central-next-prayer-label" id="central-next-prayer-name" style="margin-right: 20px;">Sonraki</span>
                <span class="central-next-prayer-time" id="central-next-prayer-time">00:00:00</span>
            </div>
        </div>

        <script>
        // Tizen TV tespiti
        var isTizenTV = navigator.userAgent.indexOf('Tizen') > -1 || navigator.userAgent.indexOf('Samsung') > -1;

        if (isTizenTV) {
            console.log('Samsung Tizen TV tespit edildi, basit mod aktif...');
        }

        // Saat güncelleme fonksiyonu
        function updateTime() {
            try {
                var now = new Date();
                var hours = now.getHours();
                var minutes = now.getMinutes();
                var seconds = now.getSeconds();

                var timeString = (hours < 10 ? '0' : '') + hours + ':' +
                               (minutes < 10 ? '0' : '') + minutes + ':' +
                               (seconds < 10 ? '0' : '') + seconds;

                var timeElement = document.getElementById('current-time');
                if (timeElement) {
                    timeElement.textContent = timeString;
                }
            } catch (e) {
                console.error('Saat güncelleme hatası:', e);
            }
        }

        // Geri sayım fonksiyonu
        function updateCountdown() {
            try {
                var now = new Date();
                var currentHour = now.getHours();
                var currentMinute = now.getMinutes();
                var currentSecond = now.getSeconds();
                var currentTimeInMinutes = currentHour * 60 + currentMinute;

                @if($prayerTime)
                @php
                    $getTimeString = function($timeValue) {
                        if (empty($timeValue)) {
                            return '00:00:00';
                        }
                        $timeStr = (string)$timeValue;
                        if (preg_match('/(\d{4}-\d{2}-\d{2})\s+(\d{2}:\d{2}(?::\d{2})?)/', $timeStr, $matches)) {
                            return $matches[2];
                        }
                        if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $timeStr)) {
                            if (substr_count($timeStr, ':') === 1) {
                                return $timeStr . ':00';
                            }
                            return $timeStr;
                        }
                        return $timeStr;
                    };
                @endphp
                var times = {
                    imsak: convertToMinutes('{{ $prayerTime->imsak ? $getTimeString($prayerTime->imsak) : "" }}'),
                    gunes: convertToMinutes('{{ $prayerTime->gunes ? $getTimeString($prayerTime->gunes) : "" }}'),
                    ogle: convertToMinutes('{{ $prayerTime->ogle ? $getTimeString($prayerTime->ogle) : "" }}'),
                    ikindi: convertToMinutes('{{ $prayerTime->ikindi ? $getTimeString($prayerTime->ikindi) : "" }}'),
                    aksam: convertToMinutes('{{ $prayerTime->aksam ? $getTimeString($prayerTime->aksam) : "" }}'),
                    yatsi: convertToMinutes('{{ $prayerTime->yatsi ? $getTimeString($prayerTime->yatsi) : "" }}')
                };

                var nextPrayer = '';
                var currentPrayer = '';
                var remainingTime = 0;

                if (currentTimeInMinutes < times.imsak) {
                    nextPrayer = 'imsak';
                    currentPrayer = 'yatsi';
                    remainingTime = times.imsak - currentTimeInMinutes;
                } else if (currentTimeInMinutes < times.gunes) {
                    nextPrayer = 'gunes';
                    currentPrayer = 'imsak';
                    remainingTime = times.gunes - currentTimeInMinutes;
                } else if (currentTimeInMinutes < times.ogle) {
                    nextPrayer = 'ogle';
                    currentPrayer = 'gunes';
                    remainingTime = times.ogle - currentTimeInMinutes;
                } else if (currentTimeInMinutes < times.ikindi) {
                    nextPrayer = 'ikindi';
                    currentPrayer = 'ogle';
                    remainingTime = times.ikindi - currentTimeInMinutes;
                } else if (currentTimeInMinutes < times.aksam) {
                    nextPrayer = 'aksam';
                    currentPrayer = 'ikindi';
                    remainingTime = times.aksam - currentTimeInMinutes;
                } else if (currentTimeInMinutes < times.yatsi) {
                    nextPrayer = 'yatsi';
                    currentPrayer = 'aksam';
                    remainingTime = times.yatsi - currentTimeInMinutes;
                } else {
                    nextPrayer = 'imsak';
                    currentPrayer = 'yatsi';
                    remainingTime = (24 * 60 - currentTimeInMinutes) + times.imsak;
                }

                var totalSeconds = remainingTime * 60 - currentSecond;
                var hours = Math.floor(totalSeconds / 3600);
                var minutes = Math.floor((totalSeconds % 3600) / 60);
                var seconds = totalSeconds % 60;

                var centralTime = document.getElementById('central-next-prayer-time');
                var centralName = document.getElementById('central-next-prayer-name');
                if (centralTime) {
                    var timeString = '';
                    if (hours < 10) timeString += '0';
                    timeString += hours + ':';
                    if (minutes < 10) timeString += '0';
                    timeString += minutes + ':';
                    if (seconds < 10) timeString += '0';
                    timeString += seconds;

                    centralTime.textContent = timeString;

                    if (centralName) {
                        var prayerNames = {
                            'imsak': 'İmsak',
                            'gunes': 'Güneş',
                            'ogle': 'Öğle',
                            'ikindi': 'İkindi',
                            'aksam': 'Akşam',
                            'yatsi': 'Yatsı'
                        };
                        centralName.textContent = prayerNames[nextPrayer] || 'Sonraki:';
                    }
                }

                var prayerBoxes = document.getElementsByClassName('prayer-box');
                for (var i = 0; i < prayerBoxes.length; i++) {
                    prayerBoxes[i].classList.remove('active');
                }

                var activeBox = document.getElementById(currentPrayer);
                if (activeBox) {
                    activeBox.classList.add('active');
                }
                @endif

            } catch (e) {
                console.error('Geri sayım hatası:', e);
            }
        }

        function convertToMinutes(timeStr) {
            if (!timeStr || timeStr === '--:--' || timeStr === '') return 0;
            var parts = timeStr.split(':');
            if (parts.length >= 2) {
                return parseInt(parts[0]) * 60 + parseInt(parts[1]);
            }
            return 0;
        }

        // Çok dilli vakit isimleri
        var prayerNameTranslations = {
            imsak: { tr: 'İmsak', de: 'Imsak', ar: 'الإمساك' },
            gunes: { tr: 'Güneş', de: 'Sonne', ar: ' الشروق' },
            ogle: { tr: 'Öğle', de: 'Mittag', ar: 'صلاة الظهر' },
            ikindi: { tr: 'İkindi', de: 'Nachmittag', ar: 'صلاة العصر' },
            aksam: { tr: 'Akşam', de: 'Abend', ar: 'صلاة المغرب' },
            yatsi: { tr: 'Yatsı', de: 'Nacht', ar: 'صلاة العشاء' }
        };

        var currentLanguageIndex = 0;
        var languages = ['tr', 'de', 'ar'];

        function updatePrayerNames() {
            try {
                var prayerBoxes = document.getElementsByClassName('prayer-box');
                for (var i = 0; i < prayerBoxes.length; i++) {
                    var box = prayerBoxes[i];
                    var prayerId = box.id;

                    if (prayerNameTranslations[prayerId]) {
                        var nameElement = box.querySelector('.prayer-name');
                        if (nameElement) {
                            nameElement.textContent = prayerNameTranslations[prayerId][languages[currentLanguageIndex]];
                        }
                    }
                }
            } catch (e) {
                console.error('Vakit isimleri güncelleme hatası:', e);
            }
        }

        function startLanguageCycle() {
            try {
                updatePrayerNames();
                currentLanguageIndex = (currentLanguageIndex + 1) % languages.length;
            } catch (e) {
                console.error('Dil döngüsü hatası:', e);
            }
        }

        // Sayfa yüklendiğinde başlat
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Sayfa yüklendi, JavaScript başlatılıyor...');

            updateTime();
            setInterval(updateTime, 1000);

            if (isTizenTV) {
                try {
                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                } catch (e) {
                    console.log('Tizen TV karmaşık geri sayım çalışmadı, basit moda geçiliyor...');
                }
            } else {
                updateCountdown();
                setInterval(updateCountdown, 1000);
            }

            updatePrayerNames();
            setInterval(startLanguageCycle, 2000);

            console.log('JavaScript başarıyla başlatıldı');
        });
        </script>
    </body>
</html>
