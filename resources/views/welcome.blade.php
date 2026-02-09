<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}</title>

        <!-- Favicon -->
        @if(\App\Models\Settings::hasFavicon())
            <link rel="icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ \App\Models\Settings::getFaviconUrl() }}">
        @endif

        <!-- Open Graph Meta Tags for Social Media Sharing -->
        <meta property="og:title" content="{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}">
        <meta property="og:description" content="{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }} - {{ __('common.meta_description') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:site_name" content="{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}">

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}">
        <meta name="twitter:description" content="{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }} - {{ __('common.meta_description') }}">

        <!-- Additional Meta Tags -->
        <meta name="description" content="{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }} - {{ __('common.meta_description') }}">
        <meta name="keywords" content="cami, üyelik, sistem, {{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}, {{ __('common.news') }}, {{ __('common.announcements') }}">
        <meta name="author" content="{{ \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi') }}">
        <meta name="robots" content="index, follow">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="//fonts.googleapis.com/css?family=Titillium+Web:400,600&amp;subset=latin-ext" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'sans': ['Titillium Web', 'Inter', 'system-ui', 'sans-serif'],
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

        <!-- Dynamic Theme CSS Variables -->
        @include('partials.theme-styles')
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Update calendar today cell color if needed
                const todayCells = document.querySelectorAll('[data-today="true"]');
                todayCells.forEach(cell => {
                    if (!cell.classList.contains('bg-green-500')) {
                        cell.style.backgroundColor = 'rgba(var(--theme-link-color-rgb), 0.1)';
                        cell.style.color = 'var(--theme-hover-color)';
                        cell.style.fontWeight = '600';
                    }
                });
            });
        </script>

    </head>
    <body class="bg-gray-50 font-sans">

        @php
            $orgName = \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi');
            $phone   = \App\Models\Settings::get('organization_phone');
            $email   = \App\Models\Settings::get('organization_email');
            $address = \App\Models\Settings::get('organization_address');
            $website = \App\Models\Settings::get('website');
            $instagram = \App\Models\Settings::get('instagram_url');
            $news = $recentNews ?? \App\Models\News::where('is_active', true)->orderByRaw('COALESCE(published_at, created_at) DESC')->limit(8)->get();
            $totalNews = \App\Models\News::where('is_active', true)->count();
            $announcements = \App\Models\Announcement::where('is_active', true)->orderBy('created_at', 'desc')->limit(5)->get();
            $totalAnnouncements = \App\Models\Announcement::where('is_active', true)->count();
            $quickAccessItems = \App\Models\QuickAccess::where('is_active', true)->orderBy('sort_order')->limit(6)->get();
            $year    = date('Y');
        @endphp

        @include('partials.top-header')
        @include('partials.main-menu')

        <!-- Hero Section -->
        <section class="bg-white text-gray-900 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
                    <!-- News Slider -->
                    <div class="lg:col-span-2 flex flex-col">
                        @if($news->count() > 0)
                            <div class="relative flex-1">
                                <div id="news-slider" class="overflow-hidden rounded-lg shadow-lg h-full" style="height: 400px;">
                                    <div class="flex transition-transform duration-500 h-full" style="width: {{ max(1, $news->count()) * 100 }}%" data-index="0">
                                        @foreach($news as $item)
                                            <div class="w-full flex-shrink-0 relative h-full" style="width: {{ 100 / max(1, $news->count()) }}%">
                                                <a href="{{ route('news.detail', $item->id) }}" class="block h-full">
                                                    @if($item->image_path)
                                                        <img src="{{ asset($item->image_path) }}" alt="{!! htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8') !!}" class="w-full h-full object-cover">
                        @else
                                                        <div class="w-full h-full bg-gradient-to-br from-slate-600/40 to-slate-700/40 flex items-center justify-center">
                                                            <i class="fas fa-newspaper text-white/70 text-4xl"></i>
                            </div>
                        @endif

                                                    <!-- Gradient overlay for better text readability -->
                                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>

                                                    <!-- News title overlay -->
                                                    <div class="absolute bottom-0 left-0 right-0 p-8">
                                                        <div class="bg-black/60 backdrop-blur-sm rounded-xl px-6 py-4 border border-white/20 shadow-2xl max-w-4xl ml-4">
                                                            <p id="current-news-title-{{ $loop->index }}" class="text-white font-bold text-xl leading-tight drop-shadow-lg {{ $loop->index === 0 ? '' : 'hidden' }}">{!! htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8') !!}</p>
                                                        </div>
                        </div>
                    </a>
                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Slider controls -->
                                <button type="button" id="news-prev" class="absolute inset-y-0 left-2 my-auto w-10 h-10 rounded-full bg-black/40 text-white flex items-center justify-center hover:bg-black/60 transition-colors">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button type="button" id="news-next" class="absolute inset-y-0 right-2 my-auto w-10 h-10 rounded-full bg-black/40 text-white flex items-center justify-center hover:bg-black/60 transition-colors">
                                    <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                        @else
                            <div class="bg-gray-100 rounded-lg shadow-lg h-96 flex items-center justify-center flex-1">
                                <div class="text-center">
                                    <i class="fas fa-newspaper text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600">Henüz haber bulunmuyor</p>
                    </div>
                </div>
                        @endif
                    </div>

                    <!-- Announcements & Prayer Times -->
                    <div class="lg:col-span-1 flex flex-col">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col h-full">
                            <!-- Header -->
                            <div class="px-4 py-3 flex-shrink-0" style="background-color: var(--theme-primary-color);">
                                <h3 class="text-white font-bold text-lg">{{ __('common.announcements') }}</h3>
                            </div>

                            <!-- Announcements Content (Half Height) -->
                            <div class="p-4 overflow-y-auto overflow-x-hidden" style="max-height: 200px; flex: 0 0 auto;">
                                @if($announcements->count() > 0)
                                    <div class="space-y-3" id="announcements-container">
                                        @foreach($announcements->take(2) as $announcement)
                                            <a href="{{ route('announcements.detail', $announcement->id) }}" class="announcement-item border-b border-gray-100 pb-3 last:border-b-0 transition-all duration-200 rounded-lg px-2 py-2 -mx-2 block" onmouseover="this.style.backgroundColor='{{ $themeHoverColor ?? '#0f766e' }}'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='inherit';">
                                                <div class="flex items-start">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-base leading-tight break-words">{{ $announcement->title }}</h4>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-bullhorn text-2xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-600 text-sm">{{ __('common.no_announcements') }}</p>
                                    </div>
                                @endif
                                
                                @if($totalAnnouncements > 2)
                                    <div class="mt-3 text-center">
                                        <a href="{{ route('announcements.all') }}" class="text-sm font-medium inline-flex items-center" style="color: {{ $themeLinkColor ?? '#0d9488' }};" onmouseover="this.style.color='{{ $themeHoverColor ?? '#0f766e' }}'" onmouseout="this.style.color='{{ $themeLinkColor ?? '#0d9488' }}'">
                                            {{ __('common.all_announcements') }}
                                            <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Prayer Times Section -->
                            @if(isset($todayPrayerTime) && $todayPrayerTime)
                            <div class="border-t border-gray-200 px-4 py-4 flex-shrink-0">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-gray-800 font-bold text-sm flex items-center">
                                        <i class="fas fa-mosque mr-2" style="color: var(--theme-link-color);"></i>
                                        {{ __('common.prayer_times') }}
                                    </h4>
                                    @php
                                        // Debug: Check if variables exist
                                        $hasNextPrayer = !empty($nextPrayer);
                                        $hasNextPrayerTime = !empty($nextPrayerTime);
                                    @endphp
                                    @if($hasNextPrayer && $hasNextPrayerTime)
                                        @php
                                            // Helper function to extract time from datetime string or use as-is
                                            $getTimeString = function($timeValue) {
                                                if (empty($timeValue)) {
                                                    return '00:00:00';
                                                }
                                                $timeStr = (string)$timeValue;
                                                // If it's a datetime string (contains date), extract only time part
                                                if (preg_match('/(\d{4}-\d{2}-\d{2})\s+(\d{2}:\d{2}(?::\d{2})?)/', $timeStr, $matches)) {
                                                    return $matches[2]; // Return only time part
                                                }
                                                // If it's already a time string (HH:MM or HH:MM:SS), use as-is
                                                if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $timeStr)) {
                                                    // Ensure seconds are present
                                                    if (substr_count($timeStr, ':') === 1) {
                                                        return $timeStr . ':00';
                                                    }
                                                    return $timeStr;
                                                }
                                                return $timeStr;
                                            };
                                            
                                            // Use the same calculation logic as /ezan page
                                            $now = \Carbon\Carbon::now();
                                            $currentHour = $now->hour;
                                            $currentMinute = $now->minute;
                                            $currentSecond = $now->second;
                                            $currentTimeInMinutes = $currentHour * 60 + $currentMinute;
                                            
                                            // Convert prayer times to minutes
                                            $convertToMinutes = function($timeStr) {
                                                if (empty($timeStr) || $timeStr === '--:--' || $timeStr === '') {
                                                    return 0;
                                                }
                                                // Extract time part if it's a datetime string
                                                if (preg_match('/(\d{4}-\d{2}-\d{2})\s+(\d{2}:\d{2}(?::\d{2})?)/', $timeStr, $matches)) {
                                                    $timeStr = $matches[2];
                                                }
                                                // Ensure format is HH:MM or HH:MM:SS
                                                if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $timeStr)) {
                                                    if (substr_count($timeStr, ':') === 1) {
                                                        $timeStr = $timeStr . ':00';
                                                    }
                                                }
                                                $parts = explode(':', $timeStr);
                                                if (count($parts) >= 2) {
                                                    return (int)$parts[0] * 60 + (int)$parts[1];
                                                }
                                                return 0;
                                            };
                                            
                                            // Get prayer times in minutes
                                            $times = [
                                                'imsak' => $convertToMinutes($getTimeString($todayPrayerTime->imsak)),
                                                'gunes' => $convertToMinutes($getTimeString($todayPrayerTime->gunes)),
                                                'ogle' => $convertToMinutes($getTimeString($todayPrayerTime->ogle)),
                                                'ikindi' => $convertToMinutes($getTimeString($todayPrayerTime->ikindi)),
                                                'aksam' => $convertToMinutes($getTimeString($todayPrayerTime->aksam)),
                                                'yatsi' => $convertToMinutes($getTimeString($todayPrayerTime->yatsi)),
                                            ];
                                            
                                            // Calculate remaining time in minutes (same logic as /ezan)
                                            $remainingTime = 0;
                                            if ($currentTimeInMinutes < $times['imsak']) {
                                                $remainingTime = $times['imsak'] - $currentTimeInMinutes;
                                            } else if ($currentTimeInMinutes < $times['gunes']) {
                                                $remainingTime = $times['gunes'] - $currentTimeInMinutes;
                                            } else if ($currentTimeInMinutes < $times['ogle']) {
                                                $remainingTime = $times['ogle'] - $currentTimeInMinutes;
                                            } else if ($currentTimeInMinutes < $times['ikindi']) {
                                                $remainingTime = $times['ikindi'] - $currentTimeInMinutes;
                                            } else if ($currentTimeInMinutes < $times['aksam']) {
                                                $remainingTime = $times['aksam'] - $currentTimeInMinutes;
                                            } else if ($currentTimeInMinutes < $times['yatsi']) {
                                                $remainingTime = $times['yatsi'] - $currentTimeInMinutes;
                                            } else {
                                                // After yatsi, next is tomorrow's imsak
                                                $remainingTime = (24 * 60 - $currentTimeInMinutes) + $times['imsak'];
                                            }
                                            
                                            // Convert to total seconds and subtract current seconds
                                            $totalSeconds = $remainingTime * 60 - $currentSecond;
                                            $hours = floor($totalSeconds / 3600);
                                            $minutes = floor(($totalSeconds % 3600) / 60);
                                            
                                            $nextPrayerNames = [
                                                'imsak' => __('common.prayer_imsak'),
                                                'gunes' => __('common.prayer_gunes'),
                                                'ogle' => __('common.prayer_ogle'),
                                                'ikindi' => __('common.prayer_ikindi'),
                                                'aksam' => __('common.prayer_aksam'),
                                                'yatsi' => __('common.prayer_yatsi'),
                                            ];
                                        @endphp
                                        <span class="text-xs text-gray-600 whitespace-nowrap">
                                            <span class="font-semibold" style="color: var(--theme-link-color);">{{ $nextPrayerNames[$nextPrayer] ?? '' }}:</span>
                                            @if($hours > 0)
                                                {{ $hours }}s {{ $minutes }}dk
                                            @else
                                                {{ $minutes }}dk
                                            @endif
                                        </span>
                                    @else
                                        <!-- Debug: Next prayer not available. Has nextPrayer: {{ $hasNextPrayer ? 'yes' : 'no' }}, Has nextPrayerTime: {{ $hasNextPrayerTime ? 'yes' : 'no' }} -->
                                    @endif
                                </div>
                                
                                @php
                                    // Helper function to extract time from datetime string or use as-is
                                    $getTimeString = function($timeValue) {
                                        if (empty($timeValue)) {
                                            return '00:00:00';
                                        }
                                        $timeStr = (string)$timeValue;
                                        // If it's a datetime string (contains date), extract only time part
                                        if (preg_match('/(\d{4}-\d{2}-\d{2})\s+(\d{2}:\d{2}(?::\d{2})?)/', $timeStr, $matches)) {
                                            return $matches[2]; // Return only time part
                                        }
                                        // If it's already a time string (HH:MM or HH:MM:SS), use as-is
                                        if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $timeStr)) {
                                            // Ensure seconds are present
                                            if (substr_count($timeStr, ':') === 1) {
                                                return $timeStr . ':00';
                                            }
                                            return $timeStr;
                                        }
                                        return $timeStr;
                                    };
                                    
                                    // Format date as Y-m-d string
                                    $dateString = $todayPrayerTime->date instanceof \Carbon\Carbon 
                                        ? $todayPrayerTime->date->format('Y-m-d')
                                        : \Carbon\Carbon::parse($todayPrayerTime->date)->format('Y-m-d');
                                    
                                    $prayers = [
                                        'imsak' => ['name' => __('common.prayer_imsak'), 'time' => $getTimeString($todayPrayerTime->imsak)],
                                        'gunes' => ['name' => __('common.prayer_gunes'), 'time' => $getTimeString($todayPrayerTime->gunes)],
                                        'ogle' => ['name' => __('common.prayer_ogle'), 'time' => $getTimeString($todayPrayerTime->ogle)],
                                        'ikindi' => ['name' => __('common.prayer_ikindi'), 'time' => $getTimeString($todayPrayerTime->ikindi)],
                                        'aksam' => ['name' => __('common.prayer_aksam'), 'time' => $getTimeString($todayPrayerTime->aksam)],
                                        'yatsi' => ['name' => __('common.prayer_yatsi'), 'time' => $getTimeString($todayPrayerTime->yatsi)],
                                    ];
                                    $currentTime = \Carbon\Carbon::now();
                                    
                                    // Calculate current prayer using the same logic as ezan.blade.php
                                    // Always recalculate to ensure accuracy
                                    // Convert current time to minutes
                                    $currentTimeInMinutes = $currentTime->hour * 60 + $currentTime->minute;
                                    
                                    // Convert prayer times to minutes
                                    $times = [];
                                    foreach ($prayers as $key => $prayer) {
                                        $timeParts = explode(':', $prayer['time']);
                                        if (count($timeParts) >= 2) {
                                            $times[$key] = (int)$timeParts[0] * 60 + (int)$timeParts[1];
                                        } else {
                                            $times[$key] = 0;
                                        }
                                    }
                                    
                                    // Determine current prayer based on ezan.blade.php logic
                                    // This logic determines which prayer period we are currently in
                                    if ($times['imsak'] > 0 && $currentTimeInMinutes < $times['imsak']) {
                                        $currentPrayer = 'yatsi'; // Previous day's yatsi
                                    } else if ($times['gunes'] > 0 && $currentTimeInMinutes < $times['gunes']) {
                                        $currentPrayer = 'imsak';
                                    } else if ($times['ogle'] > 0 && $currentTimeInMinutes < $times['ogle']) {
                                        $currentPrayer = 'gunes';
                                    } else if ($times['ikindi'] > 0 && $currentTimeInMinutes < $times['ikindi']) {
                                        $currentPrayer = 'ogle';
                                    } else if ($times['aksam'] > 0 && $currentTimeInMinutes < $times['aksam']) {
                                        $currentPrayer = 'ikindi';
                                    } else if ($times['yatsi'] > 0 && $currentTimeInMinutes < $times['yatsi']) {
                                        $currentPrayer = 'aksam';
                                    } else if ($times['yatsi'] > 0) {
                                        $currentPrayer = 'yatsi';
                                    } else {
                                        // Fallback: if times are not available, default to first prayer
                                        $currentPrayer = 'imsak';
                                    }
                                    
                                    // Calculate next prayer if not set
                                    if (!isset($nextPrayer) || !isset($nextPrayerTime)) {
                                        $nextPrayer = null;
                                        $nextPrayerTime = null;
                                        
                                        // Find next prayer
                                        foreach ($prayers as $key => $prayer) {
                                            $prayerTime = \Carbon\Carbon::parse($dateString . ' ' . $prayer['time']);
                                            if ($currentTime->lessThan($prayerTime)) {
                                                $nextPrayer = $key;
                                                $nextPrayerTime = $prayerTime;
                                                break;
                                            }
                                        }
                                        
                                        // If no next prayer found today, next is tomorrow's imsak
                                        if (!$nextPrayer) {
                                            $nextPrayer = 'imsak';
                                            $nextPrayerTime = \Carbon\Carbon::tomorrow()->setTimeFromTimeString($prayers['imsak']['time']);
                                        }
                                    }
                                    
                                    // Split prayers into two rows: first 3 and last 3
                                    $topPrayers = array_slice($prayers, 0, 3, true);
                                    $bottomPrayers = array_slice($prayers, 3, 3, true);
                                @endphp

                                <!-- Top 3 Prayers -->
                                <div class="grid grid-cols-3 gap-2 mb-2">
                                    @foreach($topPrayers as $key => $prayer)
                                        @php
                                            // Use currentPrayer from ViewServiceProvider
                                            $isActive = isset($currentPrayer) && $currentPrayer === $key;
                                        @endphp
                                        <div class="prayer-card-small rounded-lg p-2 text-center transition-all duration-300 {{ $isActive ? 'shadow-lg transform scale-105 z-10 relative' : 'bg-gray-100 hover:bg-gray-200' }}" style="{{ $isActive ? 'background: var(--theme-gradient) !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; transform: scale(1.05) !important; border: 2px solid var(--theme-secondary-color) !important;' : '' }}">
                                            <div class="text-xs font-bold mb-1 {{ $isActive ? 'text-white' : 'text-gray-700' }}" style="letter-spacing: 0.5px;">
                                                {{ $prayer['name'] }}
                                            </div>
                                            <div class="text-sm font-bold {{ $isActive ? 'text-white' : 'text-gray-800' }}" style="font-family: 'Titillium Web', monospace; {{ $isActive ? 'opacity: 0.9;' : '' }}">
                                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $prayer['time'])->format('H:i') }}
                                            </div>
                                            @if($isActive)
                                                <div class="absolute top-1 right-1">
                                                    <i class="fas fa-circle text-white text-xs animate-pulse" style="opacity: 0.8;"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Bottom 3 Prayers -->
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($bottomPrayers as $key => $prayer)
                                        @php
                                            // Use currentPrayer from ViewServiceProvider
                                            $isActive = isset($currentPrayer) && $currentPrayer === $key;
                                        @endphp
                                        <div class="prayer-card-small rounded-lg p-2 text-center transition-all duration-300 {{ $isActive ? 'shadow-lg transform scale-105 z-10 relative' : 'bg-gray-100 hover:bg-gray-200' }}" style="{{ $isActive ? 'background: var(--theme-gradient) !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important; transform: scale(1.05) !important; border: 2px solid var(--theme-secondary-color) !important;' : '' }}">
                                            <div class="text-xs font-bold mb-1 {{ $isActive ? 'text-white' : 'text-gray-700' }}" style="letter-spacing: 0.5px;">
                                                {{ $prayer['name'] }}
                                            </div>
                                            <div class="text-sm font-bold {{ $isActive ? 'text-white' : 'text-gray-800' }}" style="font-family: 'Titillium Web', monospace; {{ $isActive ? 'opacity: 0.9;' : '' }}">
                                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $prayer['time'])->format('H:i') }}
                                            </div>
                                            @if($isActive)
                                                <div class="absolute top-1 right-1">
                                                    <i class="fas fa-circle text-white text-xs animate-pulse" style="opacity: 0.8;"></i>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                                    </div>

                                            </div>
                                    </div>
        </section>

        <!-- Main Content -->
        <main class="py-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">





                <!-- Quick Access Section -->
                <section class="mb-16 mt-2 pt-0">

                    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
                        @foreach($quickAccessItems as $quickAccess)
                        @php
                            // Generate darker shade for gradient effect
                            $color = $quickAccess->icon_color ?? '#14b8a6';
                            $rgb = sscanf($color, "#%02x%02x%02x");
                            $darkerColor = sprintf("#%02x%02x%02x",
                                max(0, $rgb[0] - 30),
                                max(0, $rgb[1] - 30),
                                max(0, $rgb[2] - 30)
                            );
                        @endphp
                        <a href="{{ $quickAccess->url }}" target="_blank" class="block group relative transform transition-all duration-500 hover:-translate-y-3 hover:scale-105">
                            <!-- Glass morphism card with backdrop blur -->
                            <div class="relative overflow-hidden rounded-2xl bg-white/70 backdrop-blur-md border border-white/20 shadow-xl hover:shadow-2xl transition-all duration-500 p-4 md:p-6 text-center h-[180px] md:h-[200px] flex flex-col justify-between">

                                <!-- Subtle pattern overlay -->
                                <div class="absolute inset-0 opacity-5 group-hover:opacity-10 transition-opacity duration-300">
                                    <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <pattern id="pattern-{{ $loop->index }}" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                                <circle cx="10" cy="10" r="2" fill="currentColor"/>
                                            </pattern>
                                        </defs>
                                        <rect width="100%" height="100%" fill="url(#pattern-{{ $loop->index }})" style="color: {{ $color }}"/>
                                    </svg>
                                                                        </div>

                                <!-- Dynamic gradient background that appears on hover -->
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-20 transition-all duration-500 rounded-2xl"
                                     style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $darkerColor }} 100%)"></div>

                                <!-- Top section with icon -->
                                <div class="relative z-10">
                                    <!-- Icon with enhanced styling -->
                                    <div class="relative w-12 h-12 md:w-16 md:h-16 mx-auto mb-3 md:mb-4">
                                        <!-- Animated ring -->
                                        <div class="absolute inset-0 rounded-full opacity-20 group-hover:opacity-40 transition-all duration-500 group-hover:scale-110"
                                             style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $darkerColor }} 100%)"></div>

                                        <!-- Main icon container -->
                                        <div class="relative w-full h-full rounded-full flex items-center justify-center text-white shadow-lg group-hover:shadow-xl transition-all duration-300"
                                             style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $darkerColor }} 100%)">
                                            <i class="fas {{ $quickAccess->icon }} text-lg md:text-xl group-hover:scale-110 transition-transform duration-300"></i>
                                                            </div>

                                        <!-- Pulse effect -->
                                        <div class="absolute inset-0 rounded-full opacity-0 group-hover:opacity-30 group-hover:scale-150 transition-all duration-700"
                                             style="background: {{ $color }}"></div>
                                                            </div>

                                    <!-- Title -->
                                    <h4 class="text-sm md:text-lg font-bold text-gray-800 mb-1 md:mb-2 leading-tight group-hover:text-gray-900 transition-colors duration-300">{{ $quickAccess->title }}</h4>
                                                            </div>

                                <!-- Bottom section -->
                                <div class="relative z-10">
                                    <!-- Description -->
                                    <p class="text-xs md:text-sm text-gray-600 leading-relaxed mb-2 md:mb-4 group-hover:text-gray-700 transition-colors duration-300">{{ $quickAccess->description }}</p>

                                    <!-- Link indicator -->
                                    <div class="transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 delay-100">
                                        <div class="inline-flex items-center px-2 md:px-4 py-1 md:py-2 rounded-full text-white font-medium text-xs md:text-sm shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                                             style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $darkerColor }} 100%)">
                                            <span>{{ __('common.go_to_site') }}</span>
                                            <i class="fas fa-external-link-alt ml-2 text-xs"></i>
                                                                        </div>
                                                            </div>
                                                        </div>

                                <!-- Decorative corner elements -->
                                <div class="absolute top-0 right-0 w-20 h-20 opacity-5 group-hover:opacity-10 transition-opacity duration-300">
                                    <div class="w-full h-full rounded-bl-full" style="background: {{ $color }}"></div>
                                                                    </div>
                                <div class="absolute bottom-0 left-0 w-16 h-16 opacity-5 group-hover:opacity-10 transition-opacity duration-300">
                                    <div class="w-full h-full rounded-tr-full" style="background: {{ $color }}"></div>
                                                                </div>
                                                            </div>
                        </a>
                        @endforeach
                                                </div>

                    <!-- Events Section -->
                    @if(isset($events) && $events->count() > 0)
                    <section class="mb-16 mt-16">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                            <!-- Calendar Card - Sticky -->
                            <div class="lg:sticky lg:top-6 lg:self-start">
                                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden flex flex-col h-full">
                                    <!-- Header with background like announcements -->
                                    <div class="px-4 py-3 flex-shrink-0" style="background-color: var(--theme-primary-color);">
                                        <h3 class="text-white font-bold text-lg">{{ __('common.events') }}</h3>
                                    </div>
                                    
                                    <div class="p-6 flex-1 flex flex-col">
                                    <div class="text-center mb-4">
                                        <h3 class="text-lg font-bold text-gray-800 mb-2">{{ __('common.calendar') }}</h3>
                                        <div class="flex items-center justify-center space-x-4 mb-4">
                                            <button id="prev-month" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                                <i class="fas fa-chevron-left text-gray-600"></i>
                                            </button>
                                            <span id="current-month-year" class="text-lg font-semibold text-gray-800 min-w-[180px]"></span>
                                            <button id="next-month" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                                <i class="fas fa-chevron-right text-gray-600"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div id="calendar-container" class="calendar-grid">
                                        <!-- Calendar will be generated by JavaScript -->
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <div class="flex items-center justify-center space-x-2 text-xs text-gray-500">
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            <span>{{ __('common.event_dates') }}</span>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Events Cards Container -->
                            <div class="lg:col-span-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 h-full">
                                    <!-- Event Dates Data for JavaScript -->
                                    <script>
                                        window.eventDates = [
                                            @foreach($events as $event)
                                            '{{ $event->event_date->format('Y-m-d') }}',
                                            @endforeach
                                        ];
                                    </script>

                                    <!-- Events Cards -->
                                    @foreach($events as $event)
                                    <div class="group relative bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 transform hover:-translate-y-1 flex flex-col h-full" style="--hover-border: rgba(var(--theme-link-color-rgb), 0.3);" onmouseover="this.style.borderColor='var(--hover-border)'" onmouseout="this.style.borderColor='rgb(229, 231, 235)'">
                                        <!-- Gradient Top Bar -->
                                        <div class="h-1.5 transition-all duration-300" style="background: var(--theme-gradient);"></div>

                                        <!-- Event Image - Fixed Height (Wider) -->
                                        <div class="relative h-36 overflow-hidden bg-gray-100">
                                            @if($event->image_path)
                                            <img src="{{ asset('storage/' . $event->image_path) }}" 
                                                 alt="{{ $event->title }}" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300 cursor-pointer"
                                                 onclick="openPhotoModal('{{ asset('storage/' . $event->image_path) }}', '{{ addslashes($event->title) }}')">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
                                            @else
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <i class="fas fa-calendar-alt text-gray-400 text-3xl"></i>
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Content -->
                                        <div class="p-4 flex-1 flex flex-col">
                                            <!-- Title -->
                                            <h3 class="text-base font-bold text-gray-800 mb-3 transition-colors duration-300 leading-tight line-clamp-2" style="--hover-text: var(--theme-link-color);" onmouseover="this.style.color='var(--hover-text)'" onmouseout="this.style.color='rgb(31, 41, 55)'">
                                                {{ $event->title }}
                                            </h3>

                                            <!-- Description -->
                                            @if($event->description)
                                            <div class="mb-3 flex-1">
                                                <p class="text-gray-600 text-xs leading-relaxed line-clamp-3 group-hover:text-gray-700 transition-colors">
                                                    {{ $event->description }}
                                                </p>
                                            </div>
                                            @endif

                                            <!-- Details Section -->
                                            <div class="space-y-2 mb-3">
                                                <!-- Date & Time Combined -->
                                                <div class="flex items-center space-x-2 p-2 rounded-lg bg-gray-50 transition-colors duration-300" style="--hover-bg: rgba(var(--theme-link-color-rgb), 0.05);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='rgb(249, 250, 251)'">
                                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center transition-colors" style="background-color: rgba(var(--theme-link-color-rgb), 0.1); --hover-bg-icon: rgba(var(--theme-link-color-rgb), 0.2);" onmouseover="this.style.backgroundColor='var(--hover-bg-icon)'" onmouseout="this.style.backgroundColor='rgba(var(--theme-link-color-rgb), 0.1)'">
                                                        <i class="fas fa-calendar-alt text-xs" style="color: var(--theme-link-color);"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="text-xs font-semibold text-gray-800 mb-0.5">
                                                            {{ $event->event_date->locale(app()->getLocale())->translatedFormat('d F Y') }}
                                                        </div>
                                                        <div class="text-xs font-medium" style="color: var(--theme-link-color);">
                                                            <i class="far fa-clock mr-1"></i>{{ $event->event_date->format('H:i') }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Location -->
                                                @if($event->location)
                                                <div class="flex items-center space-x-2 p-2 rounded-lg bg-gray-50 transition-colors duration-300" style="--hover-bg: rgba(var(--theme-link-color-rgb), 0.05);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='rgb(249, 250, 251)'">
                                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                                        <i class="fas fa-map-marker-alt text-red-600 text-xs"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="text-xs font-semibold text-gray-800 line-clamp-1">
                                                            {{ $event->location }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>

                                            <!-- Footer -->
                                            <div class="pt-3 border-t border-gray-100 mt-auto">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center text-xs text-gray-500">
                                                        <i class="far fa-calendar-check mr-1.5"></i>
                                                        <span class="text-xs">{{ $event->event_date->diffForHumans() }}</span>
                                                    </div>
                                                    <!-- Share Button -->
                                                    <div class="flex items-center">
                                                        <button onclick="shareEvent({{ $event->id }}, '{{ addslashes(html_entity_decode($event->title, ENT_QUOTES | ENT_HTML5, 'UTF-8')) }}', '{{ $event->event_date->format('d.m.Y H:i') }}', '{{ addslashes(html_entity_decode($event->location ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8')) }}')" 
                                                                class="flex items-center justify-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-all duration-200 text-xs font-medium gap-1.5" 
                                                                title="Paylaş">
                                                            <i class="fas fa-share-alt text-xs"></i>
                                                            <span>{{ __('common.share') }}</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hover Gradient Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-br from-teal-500/0 to-teal-600/0 group-hover:from-teal-500/5 group-hover:to-teal-600/10 transition-all duration-300 pointer-events-none rounded-xl"></div>
                                    </div>
                                    @endforeach
                        </div>
                    </section>
                    @endif

                    <!-- Media Gallery Section -->
                    @if($galleryPhotos->count() > 0 || $galleryVideos->count() > 0)
                    <div class="mt-16">
                        <div class="text-center mb-8">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                                {{ __('common.media_gallery') }}
                            </h2>
                                                </div>

                        <!-- Tab Navigation -->
                        <div class="flex justify-center mb-8">
                            <div class="bg-gray-100 rounded-xl p-1 inline-flex">
                                <button onclick="switchMediaTab('photos')"
                                        id="photos-tab"
                                        class="tab-button active px-6 py-3 rounded-lg font-semibold text-sm transition-all duration-300 flex items-center">
                                    <i class="fas fa-images mr-2"></i>
                                    {{ __('common.photos') }}
                                </button>
                                <button onclick="switchMediaTab('videos')"
                                        id="videos-tab"
                                        class="tab-button px-6 py-3 rounded-lg font-semibold text-sm transition-all duration-300 flex items-center">
                                    <i class="fas fa-video mr-2"></i>
                                    {{ __('common.videos') }}
                                </button>
                                                </div>
                                            </div>

                        <!-- Photos Tab Content -->
                        <div id="photos-content" class="tab-content">
                            @if($galleryPhotos->count() > 0)
                            @php
                                $leadPhoto = $galleryPhotos->first();
                                $otherPhotos = $galleryPhotos->slice(1);
                            @endphp

                                                                                        <style>
                                .auto-slider-wrapper { position: relative; overflow: hidden; }
                                .auto-slider-track { display: flex; gap: 0.75rem; animation: slide-left 28s linear infinite; }
                                .auto-slider-track.alt { animation: slide-right 36s linear infinite; opacity: 0.9; }
                                .auto-slider-wrapper:hover .auto-slider-track { animation-play-state: paused; }
                                .auto-slider-item { flex: 0 0 auto; width: 140px; height: 140px; border-radius: 0.9rem; overflow: hidden; position: relative; }
                                .auto-slider-item img { width: 100%; height: 100%; object-fit: cover; filter: saturate(1.05) contrast(1.03); }
                                .auto-slider-item::after { content: ""; position: absolute; inset: 0; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.5); border-radius: inherit; pointer-events: none; }
                                .auto-slider-item:hover img { transform: scale(1.05); transition: transform 500ms ease; }
                                .auto-slider-item:hover { box-shadow: 0 10px 24px rgba(13,148,136,0.15); }
                                .edge-fade-left, .edge-fade-right { position: absolute; top: 0; bottom: 0; width: 48px; z-index: 10; pointer-events: none; }
                                .edge-fade-left { left: 0; background: linear-gradient(90deg, rgba(255,255,255,0.9), rgba(255,255,255,0)); }
                                .edge-fade-right { right: 0; background: linear-gradient(270deg, rgba(255,255,255,0.9), rgba(255,255,255,0)); }
                                /* Subtle Islamic geometric background pattern */
                                .islamic-bg { position:absolute; inset:0; z-index:0; pointer-events:none; opacity:.08; mix-blend-mode:multiply; background-size: 220px 220px; background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='220' height='220' viewBox='0 0 220 220'><defs><pattern id='p' width='55' height='55' patternUnits='userSpaceOnUse' patternTransform='rotate(45)'><path d='M27.5 0 L55 27.5 27.5 55 0 27.5Z' fill='none' stroke='%2314b8a6' stroke-width='1.2'/><circle cx='27.5' cy='27.5' r='5' fill='none' stroke='%2314b8a6' stroke-width='1.2'/></pattern></defs><rect width='100%' height='100%' fill='url(%23p)'/></svg>"); }
                                @keyframes slide-left { from { transform: translateX(0); } to { transform: translateX(-50%); } }
                                @keyframes slide-right { from { transform: translateX(-50%); } to { transform: translateX(0); } }
                                @media (min-width: 768px) { .auto-slider-item { width: 170px; height: 170px; } .edge-fade-left, .edge-fade-right { width: 64px; } }
                                @media (min-width: 1024px) { .auto-slider-item { width: 190px; height: 190px; } .edge-fade-left, .edge-fade-right { width: 80px; } }
                            </style>

                            <!-- Full-width auto-scrolling slider (lead image removed) -->
                            <div class="auto-slider-wrapper rounded-2xl ring-1 ring-gray-100/60 bg-white/60 backdrop-blur relative">
                                <!-- Decorative Islamic background -->
                                <div class="islamic-bg"></div>
                                <!-- Fade masks -->
                                <div class="edge-fade-left"></div>
                                <div class="edge-fade-right"></div>

                                @php $loopPhotos = $galleryPhotos->concat($galleryPhotos); @endphp

                                <!-- Row 1 (top) -->
                                <div class="auto-slider-track py-4 relative z-10">
                                    @foreach($loopPhotos as $photo)
                                        <div class="auto-slider-item group shadow-sm ring-1 ring-gray-100/60 bg-white/70 backdrop-blur cursor-pointer"
                                             onclick="openPhotoModal('{{ asset('storage/' . $photo->image_path) }}', '{{ $photo->title ?? $photo->alt_text ?? __('common.gallery_photo') }}')">
                                            <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->title ?? $photo->alt_text ?? __('common.gallery_photo') }}" class="transition-transform duration-500">
                                        </div>
                                    @endforeach
                                    </div>

                                <!-- Row 2 (bottom, opposite direction, slight offset) -->
                                <div class="auto-slider-track alt pb-4 -mt-2 relative z-10">
                                    @foreach($loopPhotos->reverse() as $photo)
                                        <div class="auto-slider-item group shadow-sm ring-1 ring-gray-100/60 bg-white/70 backdrop-blur cursor-pointer"
                                             onclick="openPhotoModal('{{ asset('storage/' . $photo->image_path) }}', '{{ $photo->title ?? $photo->alt_text ?? __('common.gallery_photo') }}')">
                                            <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->title ?? $photo->alt_text ?? __('common.gallery_photo') }}" class="transition-transform duration-500">
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-center mt-8">
                                <a href="{{ route('gallery.index') }}"
                                   class="inline-flex items-center px-6 py-3 text-white font-semibold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl" style="background: var(--theme-gradient);" onmouseover="this.style.background='linear-gradient(var(--theme-gradient-direction), var(--theme-hover-color), var(--theme-gradient-end))'" onmouseout="this.style.background='var(--theme-gradient)'">
                                    <i class="fas fa-images mr-2"></i>
                                    {{ __('common.view_all_photos') }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                        </div>
                            @else
                            <div class="text-center py-12">
                                <i class="fas fa-images text-gray-300 text-6xl mb-4"></i>
                                <p class="text-gray-500 text-lg">{{ __('common.no_photos') }}</p>
                    </div>
                            @endif
                </div>

                        <!-- Videos Tab Content -->
                        <div id="videos-content" class="tab-content hidden">
                            @if($galleryVideos->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($galleryVideos as $video)
                                <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 cursor-pointer"
                                     onclick="openVideoModal('{{ $video->embed_url }}', '{{ $video->title }}', '{{ $video->description ?? '' }}')">
                                    <div class="aspect-video bg-gray-200 relative">
                                        <img src="{{ $video->thumbnail_url }}"
                                             alt="{{ $video->title }}"
                                             class="w-full h-full object-cover">

                                        <!-- Play button overlay -->
                                        <div class="absolute inset-0 bg-black bg-opacity-30 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                            <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                                <i class="fas fa-play text-white text-xl ml-1"></i>
                            </div>
                        </div>

                                        <!-- Video duration badge -->
                                        <div class="absolute top-3 right-3 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ __('common.video') }}
                            </div>
                        </div>

                                    <!-- Video info -->
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 transition-colors duration-300" style="--hover-text: var(--theme-link-color);" onmouseover="this.style.color='var(--hover-text)'" onmouseout="this.style.color='rgb(17, 24, 39)'">
                                            {{ $video->title }}
                                        </h3>

                                        @if($video->description)
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            {{ $video->description }}
                                        </p>
                            @endif

                                        @if($video->category)
                                        <div class="mt-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="background-color: rgba(var(--theme-link-color-rgb), 0.1); color: var(--theme-hover-color);">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ $video->category->name }}
                                            </span>
                            </div>
                            @endif
                        </div>
                    </div>
                                @endforeach
            </div>

                            <!-- Videos View More Button -->
                            <div class="text-center mt-8">
                                <a href="{{ route('video-gallery.index') }}"
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-video mr-2"></i>
                                    {{ __('common.view_all_videos') }}
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                            @else
                            <div class="text-center py-12">
                                <i class="fas fa-video text-gray-300 text-6xl mb-4"></i>
                                <p class="text-gray-500 text-lg">{{ __('common.no_videos') }}</p>
                                </div>
                            @endif
                            </div>
                        </div>
                            @endif

                    <!-- Photo Modal -->
                    <div id="photoModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
                        <div class="relative max-w-4xl max-h-[90vh] w-full">
                            <!-- Close button -->
                            <button onclick="closePhotoModal()"
                                    class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors duration-200 z-10">
                                <i class="fas fa-times text-3xl"></i>
                            </button>

                            <!-- Photo -->
                            <img id="modalPhoto" src="" alt="" class="max-w-full max-h-[85vh] w-auto h-auto object-contain rounded-lg mx-auto">

                            <!-- Photo title -->
                            <div id="modalPhotoTitle" class="text-white text-center mt-4 text-lg font-medium"></div>
                        </div>
                    </div>

                    <!-- Video Modal -->
                    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
                        <div class="relative max-w-5xl max-h-full w-full">
                            <!-- Close button -->
                            <button onclick="closeVideoModal()"
                                    class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors duration-200">
                                <i class="fas fa-times text-3xl"></i>
                            </button>

                            <!-- Video Container -->
                            <div class="aspect-video bg-black rounded-lg overflow-hidden">
                                <iframe id="modalVideo"
                                        src=""
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        class="w-full h-full">
                                </iframe>
                            </div>

                            <!-- Video info -->
                            <div class="text-white text-center mt-4">
                                <h3 id="modalVideoTitle" class="text-xl font-semibold mb-2"></h3>
                                <p id="modalVideoDescription" class="text-gray-300"></p>
                        </div>
                    </div>
                </div>

                    <!-- Staggered animation CSS -->
                    <style>
                        @keyframes float {
                            0%, 100% { transform: translateY(0px); }
                            50% { transform: translateY(-5px); }
                        }

                        .group:nth-child(1) { animation-delay: 0ms; }
                        .group:nth-child(2) { animation-delay: 100ms; }
                        .group:nth-child(3) { animation-delay: 200ms; }
                        .group:nth-child(4) { animation-delay: 300ms; }
                        .group:nth-child(5) { animation-delay: 400ms; }
                        .group:nth-child(6) { animation-delay: 500ms; }

                        .group:hover {
                            animation: float 2s ease-in-out infinite;
                        }

                        /* Enhanced backdrop blur support */
                        @supports (backdrop-filter: blur(10px)) {
                            .backdrop-blur-md {
                                backdrop-filter: blur(10px);
                            }
                        }

                        /* Line clamp utility */
                        .line-clamp-2 {
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                        }

                        /* Tab styles */
                        .tab-button {
                            color: #6b7280;
                            background: transparent;
                        }

                        .tab-button.active {
                            color: #ffffff;
                            background: var(--theme-gradient, linear-gradient(135deg, #14b8a6 0%, #0d9488 100%));
                            box-shadow: 0 4px 12px rgba(var(--theme-link-color-rgb, 20, 184, 166), 0.3);
                        }

                        .tab-button:hover:not(.active) {
                            color: var(--theme-link-color, #374151);
                            background: rgba(var(--theme-link-color-rgb, 13, 148, 136), 0.1);
                        }

                        /* Fallback for browsers without backdrop-filter support */
                        @supports not (backdrop-filter: blur(10px)) {
                            .backdrop-blur-md {
                                background: rgba(255, 255, 255, 0.8);
                            }
                        }
                    </style>
                </section>


                </div>
        </main>

        @include('partials.cookie-consent')
        @include('partials.footer')

        <script>
            let currentAnnouncementId = null;
            let currentAnnouncementTitle = null;

            function toggleMobileMenu() {
                const menu = document.getElementById('mobile-menu');
                menu.classList.toggle('hidden');
            }

            // Share Announcement Function
            function shareAnnouncement(announcementId, title) {
                const url = window.location.origin + '/duyurular#' + announcementId;

                // Get announcement details for better sharing
                const detailElement = document.querySelector(`#announcement-detail-${announcementId} .announcement-full-data`);
                const type = detailElement ? detailElement.dataset.type : 'general';

                let shareText, shareTitle;

                if (type === 'obituary') {
                    // Special sharing for obituary announcements
                    const obituaryName = detailElement.dataset.obituaryName || '';
                    const obituaryDate = detailElement.dataset.obituaryDate || '';
                    const funeralTime = detailElement.dataset.funeralTime || '';
                    const funeralPlace = detailElement.dataset.funeralPlace || '';

                    shareTitle = `Vefat Duyurusu - ${obituaryName}`;
                    shareText = `Vefat Duyurusu\n\n`;
                    if (obituaryName) shareText += `Vefat Eden: ${obituaryName}\n`;
                    if (obituaryDate) shareText += `Vefat Tarihi: ${obituaryDate}\n`;
                    if (funeralTime) shareText += `Cenaze Namazı: ${funeralTime}\n`;
                    if (funeralPlace) shareText += `Namaz Yeri: ${funeralPlace}\n`;
                    shareText += `\nDetaylı bilgi için: ${url}`;
                } else {
                    // General announcement sharing
                    shareTitle = title;
                    shareText = `"${title}"\n\nDetaylı bilgi için: ${url}`;
                }

                if (navigator.share) {
                    navigator.share({
                        title: shareTitle,
                        text: shareText,
                        url: url
                    }).catch(console.error);
                } else {
                    // Fallback: Copy to clipboard
                    navigator.clipboard.writeText(shareText).then(() => {
                        showToast(type === 'obituary' ? 'Vefat duyurusu kopyalandı!' : 'Duyuru linki kopyalandı!', 'success');
                    }).catch(() => {
                        // Fallback for older browsers
                        const textArea = document.createElement('textarea');
                        textArea.value = shareText;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                        showToast(type === 'obituary' ? 'Vefat duyurusu kopyalandı!' : 'Duyuru linki kopyalandı!', 'success');
                    });
                }
            }

            // Share Current Announcement (from modal)
            function shareCurrentAnnouncement() {
                if (currentAnnouncementId && currentAnnouncementTitle) {
                    shareAnnouncement(currentAnnouncementId, currentAnnouncementTitle);
                }
            }

            // Get current announcement data for sharing
            function getCurrentAnnouncementData() {
                if (!currentAnnouncementId) return null;

                const detailElement = document.querySelector(`#announcement-detail-${currentAnnouncementId} .announcement-full-data`);
                if (!detailElement) return null;

                const url = window.location.origin + '/duyurular#' + currentAnnouncementId;
                const title = detailElement.dataset.title;
                const type = detailElement.dataset.type;

                let shareText = `"${title}"\n\n`;

                if (type === 'obituary') {
                    const obituaryName = detailElement.dataset.obituaryName || '';
                    const obituaryDate = detailElement.dataset.obituaryDate || '';
                    const funeralTime = detailElement.dataset.funeralTime || '';
                    const funeralPlace = detailElement.dataset.funeralPlace || '';

                    shareText = `Vefat Duyurusu\n\n`;
                    if (obituaryName) shareText += `${obituaryName} Vefat Etmiştir.\n\n`;

                    if (obituaryName) shareText += `Vefat Eden: ${obituaryName}\n`;
                    if (obituaryDate) shareText += `Vefat Tarihi: ${obituaryDate}\n`;
                    if (funeralTime) shareText += `Cenaze Namazı: ${funeralTime}\n`;
                    if (funeralPlace) shareText += `Namaz Yeri: ${funeralPlace}\n\n`;

                    shareText += `Vefat eden kardeşimize Allah'tan rahmet, yakınlarına başsağlığı dileriz\n\n`;
                    shareText += `Yönetim Kurulu Adına\n`;
                    shareText += `${detailElement.dataset.presidentName || 'Dernek Başkanı'}\n`;
                    shareText += `Dernek Başkanı\n\n`;
                }

                shareText += `\nDetaylı bilgi için: ${url}`;

                return {
                    url: url,
                    title: title,
                    text: shareText
                };
            }

            // Share to WhatsApp
            function shareToWhatsApp() {
                const data = getCurrentAnnouncementData();
                if (!data) return;

                const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(data.text)}`;
                window.open(whatsappUrl, '_blank');
                showToast('WhatsApp\'ta paylaşılıyor...', 'success');
            }


            // Share to Twitter
            function shareToTwitter() {
                const data = getCurrentAnnouncementData();
                if (!data) return;

                const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(data.text)}&url=${encodeURIComponent(data.url)}`;
                window.open(twitterUrl, '_blank', 'width=600,height=400');
                showToast('Twitter\'da paylaşılıyor...', 'success');
            }

            // Media Gallery Tab Switching
            function switchMediaTab(tabName) {
                // Remove active class from all tabs
                document.querySelectorAll('.tab-button').forEach(button => {
                    button.classList.remove('active');
                });

                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Add active class to clicked tab
                document.getElementById(tabName + '-tab').classList.add('active');

                // Show selected tab content
                document.getElementById(tabName + '-content').classList.remove('hidden');
            }

            // Photo Modal Functions
            function openPhotoModal(imageSrc, imageTitle) {
                document.getElementById('modalPhoto').src = imageSrc;
                document.getElementById('modalPhoto').alt = imageTitle;
                document.getElementById('modalPhotoTitle').textContent = imageTitle;
                document.getElementById('photoModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }

            function closePhotoModal() {
                document.getElementById('photoModal').classList.add('hidden');
                document.body.style.overflow = 'auto'; // Restore scrolling
            }

            // Close modal when clicking outside the image
            document.addEventListener('click', function(event) {
                const modal = document.getElementById('photoModal');
                if (event.target === modal) {
                    closePhotoModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closePhotoModal();
                    closeVideoModal();
                }
            });

            // Video Modal Functions
            function openVideoModal(embedUrl, videoTitle, videoDescription) {
                document.getElementById('modalVideo').src = embedUrl;
                document.getElementById('modalVideoTitle').textContent = videoTitle;
                document.getElementById('modalVideoDescription').textContent = videoDescription;
                document.getElementById('videoModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }

            function closeVideoModal() {
                document.getElementById('videoModal').classList.add('hidden');
                document.getElementById('modalVideo').src = ''; // Stop video playback
                document.body.style.overflow = 'auto'; // Restore scrolling
            }

            // Close video modal when clicking outside
            document.addEventListener('click', function(event) {
                const videoModal = document.getElementById('videoModal');
                if (event.target === videoModal) {
                    closeVideoModal();
                }
            });

                    // Share to Instagram
        function shareToInstagram() {
            const data = getCurrentAnnouncementData();
            if (!data) return;

            // Instagram doesn't have direct sharing API, so we'll copy the text to clipboard
            if (navigator.clipboard) {
                navigator.clipboard.writeText(data.text).then(() => {
                    showToast('Metin kopyalandı! Instagram\'da paylaşabilirsiniz.', 'success');
                }).catch(() => {
                    fallbackCopyTextToClipboard(data.text);
                });
            } else {
                fallbackCopyTextToClipboard(data.text);
            }
        }

            // Copy announcement link
            function copyAnnouncementLink() {
                const data = getCurrentAnnouncementData();
                if (!data) return;

                if (navigator.clipboard) {
                    navigator.clipboard.writeText(data.url).then(() => {
                        showToast('Link kopyalandı!', 'success');
                    }).catch(() => {
                        fallbackCopyTextToClipboard(data.url);
                    });
                } else {
                    fallbackCopyTextToClipboard(data.url);
                }
            }

            // Fallback copy function for older browsers
            function fallbackCopyTextToClipboard(text) {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                try {
                    document.execCommand('copy');
                    showToast('Link kopyalandı!', 'success');
                } catch (err) {
                    showToast('Link kopyalanamadı', 'error');
                }

                document.body.removeChild(textArea);
            }

            // Show Toast Notification
            function showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full flex items-center space-x-2`;

                if (type === 'success') {
                    toast.className += ' bg-green-500';
                    toast.innerHTML = `<i class="fas fa-check-circle"></i><span>${message}</span>`;
                } else if (type === 'error') {
                    toast.className += ' bg-red-500';
                    toast.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${message}</span>`;
                } else {
                    toast.className += ' bg-blue-500';
                    toast.innerHTML = `<i class="fas fa-info-circle"></i><span>${message}</span>`;
                }

                document.body.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Animate out and remove
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                        }
                    }, 300);
                }, 3000);
            }


            // Announcement styling update
            document.addEventListener('DOMContentLoaded', function() {
                const style = document.createElement('style');
                style.textContent = `
                    .announcement-item {
                            transition: all 0.2s ease;
                    }
                    .announcement-item:hover {
                            background-color: {{ $themeHoverColor ?? '#0f766e' }} !important;
                            color: white !important;
                        }
                        .announcement-item:hover h4 {
                            color: white !important;
                    }
                `;
                document.head.appendChild(style);

                // Simple news slider
                const slider = document.getElementById('news-slider');
                if (slider) {
                    const track = slider.querySelector('.flex');
                    const total = track.children.length;
                    let idx = 0;

                    function go(to) {
                        if (total === 0) return;
                        idx = (to + total) % total;
                        const percent = -(idx * (100 / total));
                        track.style.transform = `translateX(${percent}%)`;

                        // Hide all titles and show current one
                        const allTitles = document.querySelectorAll('[id^="current-news-title-"]');
                        allTitles.forEach((title, i) => {
                            title.classList.toggle('hidden', i !== idx);
                        });
                    }

                    const prev = document.getElementById('news-prev');
                    const next = document.getElementById('news-next');
                    if (prev) prev.addEventListener('click', () => go(idx - 1));
                    if (next) next.addEventListener('click', () => go(idx + 1));

                    // auto-rotate
                    setInterval(() => go(idx + 1), 6000);
                }
            });
        </script>

        <!-- Cookie Policy -->
        <div id="cookie-banner" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50 transform translate-y-full transition-transform duration-300">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-cookie-bite mr-2" style="color: var(--theme-link-color);"></i>
                            <h3 class="text-lg font-semibold text-gray-900">Çerez Politikası</h3>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Web sitemizde size en iyi deneyimi sunabilmek için çerezler kullanıyoruz.
                            Sitemizi kullanmaya devam ederek çerez kullanımını kabul etmiş olursunuz.
                            <a href="/sayfa/cerez-politikasi" class="underline ml-1" style="color: var(--theme-link-color);" onmouseover="this.style.color='var(--theme-hover-color)'" onmouseout="this.style.color='var(--theme-link-color)'">Detaylı bilgi</a>
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button onclick="acceptCookies()" class="px-4 py-2 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center" style="background-color: var(--theme-button-color);" onmouseover="this.style.backgroundColor='var(--theme-hover-color)'" onmouseout="this.style.backgroundColor='var(--theme-button-color)'">
                            <i class="fas fa-check mr-2"></i>
                            Kabul Et
                        </button>
                        <button onclick="declineCookies()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>
                            Reddet
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Cookie Policy Functions
            function showCookieBanner() {
                const banner = document.getElementById('cookie-banner');
                const consent = getCookie('cookie_consent');

                // Banner'ı göster eğer:
                // 1. Hiç çerez yoksa VEYA
                // 2. Çerez reddedilmişse (declined)
                if (!consent || consent === 'declined') {
                    setTimeout(() => {
                        banner.classList.remove('translate-y-full');
                    }, 1000);
                }
            }

            function acceptCookies() {
                setCookie('cookie_consent', 'accepted', 365);
                hideCookieBanner();
                showToast('Çerez tercihleriniz kaydedildi!', 'success');
            }

            function declineCookies() {
                setCookie('cookie_consent', 'declined', 1); // 1 gün sakla
                hideCookieBanner();
                showToast('Çerez tercihleriniz kaydedildi!', 'info');
            }

            function hideCookieBanner() {
                const banner = document.getElementById('cookie-banner');
                banner.classList.add('translate-y-full');
            }

            function setCookie(name, value, days) {
                const expires = new Date();
                expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
                document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
            }

            function getCookie(name) {
                const nameEQ = name + "=";
                const ca = document.cookie.split(';');
                for(let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                    if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
                }
                return null;
            }

            // Show cookie banner on page load
            document.addEventListener('DOMContentLoaded', function() {
                showCookieBanner();

                // No external widget to check anymore
            });
        </script>

        <!-- Calendar Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Calendar functionality
                let currentDate = new Date();
                let currentMonth = currentDate.getMonth();
                let currentYear = currentDate.getFullYear();

                const monthNames = {
                    'tr': ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
                    'de': ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember']
                };

                const dayNames = {
                    'tr': ['Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt', 'Paz'],
                    'de': ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So']
                };

                const locale = '{{ app()->getLocale() }}';

                function renderCalendar() {
                    const firstDay = new Date(currentYear, currentMonth, 1);
                    const lastDay = new Date(currentYear, currentMonth + 1, 0);
                    const daysInMonth = lastDay.getDate();
                    const startingDayOfWeek = firstDay.getDay();
                    const adjustedStartingDay = startingDayOfWeek === 0 ? 6 : startingDayOfWeek - 1; // Monday = 0

                    const monthYearElement = document.getElementById('current-month-year');
                    if (monthYearElement) {
                        monthYearElement.textContent = `${monthNames[locale][currentMonth]} ${currentYear}`;
                    }

                    const calendarContainer = document.getElementById('calendar-container');
                    if (!calendarContainer) return;

                    // Clear previous calendar
                    calendarContainer.innerHTML = '';

                    // Add day headers
                    const dayHeaders = dayNames[locale];
                    dayHeaders.forEach(day => {
                        const dayHeader = document.createElement('div');
                        dayHeader.className = 'text-center text-xs font-semibold text-gray-600 py-2';
                        dayHeader.textContent = day;
                        calendarContainer.appendChild(dayHeader);
                    });

                    // Add empty cells for days before the first day of the month
                    for (let i = 0; i < adjustedStartingDay; i++) {
                        const emptyCell = document.createElement('div');
                        emptyCell.className = 'h-8';
                        calendarContainer.appendChild(emptyCell);
                    }

                    // Add days of the month
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(currentYear, currentMonth, day);
                        const dateString = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        
                        const isToday = date.getTime() === today.getTime();
                        const hasEvent = window.eventDates && window.eventDates.includes(dateString);

                        const dayCell = document.createElement('div');
                        dayCell.className = 'h-8 flex items-center justify-center text-sm rounded-lg transition-colors cursor-pointer';
                        
                        if (hasEvent) {
                            dayCell.className += ' bg-green-500 text-white font-semibold hover:bg-green-600';
                        } else if (isToday) {
                            const linkColorRgb = getComputedStyle(document.documentElement).getPropertyValue('--theme-link-color-rgb').trim();
                            const hoverColor = getComputedStyle(document.documentElement).getPropertyValue('--theme-hover-color').trim();
                            if (linkColorRgb && hoverColor) {
                                dayCell.style.backgroundColor = `rgba(${linkColorRgb}, 0.1)`;
                                dayCell.style.color = hoverColor;
                                dayCell.style.fontWeight = '600';
                            } else {
                                dayCell.className += ' bg-teal-100 text-teal-700 font-semibold';
                            }
                        } else {
                            dayCell.className += ' text-gray-700 hover:bg-gray-100';
                        }

                        dayCell.textContent = day;
                        calendarContainer.appendChild(dayCell);
                    }
                }

                // Previous month button
                const prevButton = document.getElementById('prev-month');
                if (prevButton) {
                    prevButton.addEventListener('click', function() {
                        currentMonth--;
                        if (currentMonth < 0) {
                            currentMonth = 11;
                            currentYear--;
                        }
                        renderCalendar();
                    });
                }

                // Next month button
                const nextButton = document.getElementById('next-month');
                if (nextButton) {
                    nextButton.addEventListener('click', function() {
                        currentMonth++;
                        if (currentMonth > 11) {
                            currentMonth = 0;
                            currentYear++;
                        }
                        renderCalendar();
                    });
                }

                // Initial render
                renderCalendar();
            });
        </script>

        <style>
            .calendar-grid {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 4px;
            }
            
            /* Ensure calendar card matches event card height */
            .lg\:sticky > div {
                min-height: 100%;
            }
            
            /* Make calendar container flexible to fill available space */
            #calendar-container {
                min-height: 200px;
            }
        </style>

        <script>
            // Event Share Function - Native Share API kullan
            window.shareEvent = function(eventId, title, date, location) {
                const url = window.location.origin + '/etkinlikler-liste';
                const orgName = @json(\App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi'));

                let shareText = title + "\n\n";
                shareText += "Tarih: " + date + "\n";
                if (location) {
                    shareText += "Konum: " + location + "\n";
                }
                shareText += "\n" + orgName + "\n\n";
                shareText += "Detaylı bilgi için: " + url;

                // Native Share API kullan (tüm paylaşım seçeneklerini gösterir)
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        text: shareText,
                        url: url
                    }).then(() => {
                        // Paylaşım başarılı
                    }).catch((error) => {
                        // Kullanıcı paylaşımı iptal etti veya hata oluştu
                        if (error.name !== 'AbortError') {
                            console.error('Paylaşım hatası:', error);
                        }
                    });
                } else {
                    // Native Share API desteklenmiyorsa linki kopyala
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(shareText).then(() => {
                            showEventToast('Metin kopyalandı!', 'success');
                        }).catch(() => {
                            const textArea = document.createElement('textarea');
                            textArea.value = shareText;
                            document.body.appendChild(textArea);
                            textArea.select();
                            document.execCommand('copy');
                            document.body.removeChild(textArea);
                            showEventToast('Metin kopyalandı!', 'success');
                        });
                    } else {
                        const textArea = document.createElement('textarea');
                        textArea.value = shareText;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                        showEventToast('Metin kopyalandı!', 'success');
                    }
                }
            };

            function showEventToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium transition-all duration-300 transform translate-x-full flex items-center space-x-2`;

                if (type === 'success') {
                    toast.className += ' bg-green-500';
                    toast.innerHTML = `<i class="fas fa-check-circle"></i><span>${message}</span>`;
                } else if (type === 'error') {
                    toast.className += ' bg-red-500';
                    toast.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${message}</span>`;
                } else {
                    toast.className += ' bg-blue-500';
                    toast.innerHTML = `<i class="fas fa-info-circle"></i><span>${message}</span>`;
                }

                document.body.appendChild(toast);

                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full');
                }, 100);

                // Animate out and remove
                setTimeout(() => {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (document.body.contains(toast)) {
                            document.body.removeChild(toast);
                        }
                    }, 300);
                }, 3000);
            }
        </script>
    </body>
</html>
