<?php

namespace App\Providers;

use App\Models\PrayerTime;
use App\Models\Settings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share today's prayer times with all views
        View::composer('*', function ($view) {
            // Initialize default values (safe fallback)
            $prayerTime = null;
            $currentPrayer = null;
            $nextPrayer = null;
            $nextPrayerTime = null;
            $timeUntilNext = null;

            try {
                $today = Carbon::today()->format('Y-m-d');
                
                // Priority: Garbsen > Helmstedt > Other
                $prayerTime = PrayerTime::whereDate('date', $today)
                    ->where(function($query) {
                        $query->where('city', 'Garbsen')
                              ->orWhere('city', 'Helmstedt');
                    })
                    ->orderByRaw("CASE WHEN city = 'Garbsen' THEN 1 WHEN city = 'Helmstedt' THEN 2 ELSE 3 END")
                    ->first();

                // If not found, get any city
                if (!$prayerTime) {
                    $prayerTime = PrayerTime::whereDate('date', $today)->first();
                }

                // Calculate current and next prayer only if prayer time exists
                if ($prayerTime) {
                // Helper function to format date as Y-m-d string
                $formatDate = function($date) {
                    return $date instanceof Carbon 
                        ? $date->format('Y-m-d')
                        : Carbon::parse($date)->format('Y-m-d');
                };
                
                // Helper function to extract time from datetime string or use as-is
                $getTimeString = function($timeValue) {
                    if (empty($timeValue)) {
                        return '00:00:00'; // Default time
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
                $dateString = $formatDate($prayerTime->date);
                
                // Get current time
                $currentTime = Carbon::now();
                
                $prayers = [
                    'imsak' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->imsak)),
                    'gunes' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->gunes)),
                    'ogle' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->ogle)),
                    'ikindi' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->ikindi)),
                    'aksam' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->aksam)),
                    'yatsi' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->yatsi)),
                ];

                // Find current prayer
                $prayerKeys = array_keys($prayers);
                foreach ($prayers as $name => $time) {
                    $prevIndex = array_search($name, $prayerKeys);
                    $prevName = ($prevIndex !== false && $prevIndex > 0) ? $prayerKeys[$prevIndex - 1] : 'yatsi';
                    
                    $prevTime = $prevName === 'yatsi' 
                        ? Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->yatsi))->subDay()
                        : $prayers[$prevName];
                    
                    // Check if current time is between previous prayer and this prayer
                    if ($currentTime->greaterThanOrEqualTo($prevTime) && $currentTime->lessThan($time)) {
                        $currentPrayer = $name;
                        break;
                    }
                }

                // If current time is after yatsi (before tomorrow's imsak), current prayer is yatsi
                if (!$currentPrayer && $currentTime->greaterThan($prayers['yatsi'])) {
                    $currentPrayer = 'yatsi';
                }
                
                // If still no current prayer found and before imsak, set to yatsi (previous day)
                if (!$currentPrayer && $currentTime->lessThan($prayers['imsak'])) {
                    $currentPrayer = 'yatsi';
                }

                // If current time is after yatsi, next prayer is tomorrow's imsak
                if ($currentTime->greaterThan($prayers['yatsi'])) {
                    $tomorrow = PrayerTime::whereDate('date', Carbon::tomorrow()->format('Y-m-d'))
                        ->where('city', $prayerTime->city)
                        ->first();
                    
                    if ($tomorrow) {
                        $nextPrayer = 'imsak';
                        $tomorrowDateString = $formatDate($tomorrow->date);
                        $nextPrayerTime = Carbon::parse($tomorrowDateString . ' ' . $getTimeString($tomorrow->imsak));
                    }
                } else {
                    // Find next prayer today
                    foreach ($prayers as $name => $time) {
                        if ($currentTime->lessThan($time)) {
                            $nextPrayer = $name;
                            $nextPrayerTime = $time;
                            break;
                        }
                    }
                }

                if ($nextPrayerTime) {
                    $timeUntilNext = $currentTime->diffInSeconds($nextPrayerTime);
                }
                }
            } catch (\Exception $e) {
                // Log error but don't break other pages
                \Log::warning('ViewServiceProvider: Prayer time error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Keep default null values - pages will work without prayer times
            }

            // Get theme settings
            $useGradient = Settings::get('theme_use_gradient', '1') == '1';
            $primaryColor = Settings::get('theme_primary_color', '#085952');
            $secondaryColor = Settings::get('theme_secondary_color', '#0a7b73');
            $gradientStart = Settings::get('theme_gradient_start', '#076961');
            $gradientEnd = Settings::get('theme_gradient_end', '#0a7b6e');
            $gradientDirection = Settings::get('theme_gradient_direction', 'to right');
            $hoverColor = Settings::get('theme_hover_color', '#0f766e');
            $buttonColor = Settings::get('theme_button_color', '#0d9488');
            $linkColor = Settings::get('theme_link_color', '#0d9488');
            
            // Calculate gradient CSS
            $gradientCss = $useGradient 
                ? "linear-gradient({$gradientDirection}, {$gradientStart}, {$gradientEnd})"
                : $primaryColor;

            // Always share variables (null is safe for views)
            $view->with([
                'todayPrayerTime' => $prayerTime,
                'currentPrayer' => $currentPrayer,
                'nextPrayer' => $nextPrayer,
                'nextPrayerTime' => $nextPrayerTime,
                'timeUntilNext' => $timeUntilNext,
                'themePrimaryColor' => $primaryColor,
                'themeSecondaryColor' => $secondaryColor,
                'themeGradientStart' => $gradientStart,
                'themeGradientEnd' => $gradientEnd,
                'themeGradientDirection' => $gradientDirection,
                'themeUseGradient' => $useGradient,
                'themeHoverColor' => $hoverColor,
                'themeButtonColor' => $buttonColor,
                'themeLinkColor' => $linkColor,
                'themeGradientCss' => $gradientCss,
            ]);
        });
    }
}
