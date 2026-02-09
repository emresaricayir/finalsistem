<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\BoardMember;
use App\Models\Event;
use App\Models\GalleryImage;
use App\Models\News;
use App\Models\Page;
use App\Models\PrayerTime;
use App\Models\Settings;
use App\Models\VideoGallery;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicController extends Controller
{
    /**
     * Display the welcome page
     */
    public function welcome()
    {
        // Get recent news - en yeni tarihli önce, en eski tarihli en sonda gösterilecek
        $recentNews = News::where('is_active', true)
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->limit(8)
            ->get();

        // Get recent gallery images
        $galleryPhotos = GalleryImage::with('category')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        // Get recent videos
        $galleryVideos = VideoGallery::with('category')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Get upcoming events (active and future events)
        $events = Event::where('is_active', true)
            ->where('event_date', '>=', Carbon::now())
            ->orderBy('event_date', 'asc')
            ->limit(6)
            ->get();

        return view('welcome', compact('recentNews', 'galleryPhotos', 'galleryVideos', 'events'));
    }

    /**
     * Display all announcements
     */
    public function announcements()
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
        $announcements = Announcement::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('announcements.all', compact('announcements', 'orgName'));
    }

    /**
     * Display announcement detail
     */
    public function announcementDetail($id)
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
        $announcement = Announcement::where('is_active', true)
            ->findOrFail($id);

        return view('announcements.detail', compact('announcement', 'orgName'));
    }

    /**
     * Display all news
     */
    public function news()
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
        $news = News::where('is_active', true)
            ->orderByRaw('COALESCE(published_at, created_at) DESC')
            ->paginate(10);

        return view('news.all', compact('news', 'orgName'));
    }

    /**
     * Display news detail
     */
    public function newsDetail($id)
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
        $news = News::where('is_active', true)
            ->findOrFail($id);

        return view('news.detail', compact('news', 'orgName'));
    }

    /**
     * Site-wide search
     */
    public function search(Request $request)
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
        $q = trim($request->get('q', ''));
        $newsResults = collect();
        $announcementResults = collect();
        $pageResults = collect();

        if ($q !== '') {
            $newsResults = News::where('is_active', true)
                ->where(function($w) use ($q){
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%");
                })->orderByRaw('COALESCE(published_at, created_at) DESC')->limit(20)->get();

            $announcementResults = Announcement::where('is_active', true)
                ->where(function($w) use ($q){
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%");
                })->orderBy('created_at', 'desc')->limit(20)->get();

            $pageResults = Page::where('is_active', true)
                ->where(function($w) use ($q){
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('content', 'like', "%{$q}%");
                })->orderBy('created_at', 'desc')->limit(20)->get();
        }

        return view('search.results', compact('orgName','q','newsResults','announcementResults','pageResults'));
    }

    /**
     * Display board members
     */
    public function boardMembers()
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');

        // Sadece "Yönetim Kurulu" kategorisindeki üyeler
        $boardMembers = BoardMember::where('is_active', true)
            ->whereHas('category', function($query) {
                $query->where('name', 'Yönetim Kurulu');
            })
            ->orderBy('name') // Alfabetik sıralama
            ->get();

        return view('board-members.index', compact('boardMembers', 'orgName'));
    }

    /**
     * Display page detail
     */
    public function page($slug)
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('page.show', compact('page', 'orgName'));
    }

    /**
     * Display prayer times (Ezan) page
     */
    public function ezan()
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
        
        // Get today's prayer times
        // Priority: Garbsen > Helmstedt > Other
        $today = Carbon::today()->format('Y-m-d');
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

        // Calculate next prayer time
        $currentTime = Carbon::now();
        $nextPrayer = null;
        $nextPrayerTime = null;
        $timeUntilNext = null;

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
            
            $prayers = [
                'imsak' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->imsak)),
                'gunes' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->gunes)),
                'ogle' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->ogle)),
                'ikindi' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->ikindi)),
                'aksam' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->aksam)),
                'yatsi' => Carbon::parse($dateString . ' ' . $getTimeString($prayerTime->yatsi)),
            ];

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

        // Calculate Sabah (Fajr) prayer time (Imsak + 10 minutes)
        $sabahPrayerTime = null;
        if ($prayerTime && $prayerTime->imsak) {
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
            
            $formatDate = function($date) {
                return $date instanceof Carbon 
                    ? $date->format('Y-m-d')
                    : Carbon::parse($date)->format('Y-m-d');
            };
            
            $imsakTime = Carbon::parse($formatDate($prayerTime->date) . ' ' . $getTimeString($prayerTime->imsak));
            $sabahPrayerTime = $imsakTime->copy()->addMinutes(10);
        }

        // Calculate Friday prayer time (same as Ogle/Dhuhr)
        $fridayPrayerTime = null;
        if ($prayerTime && $prayerTime->ogle) {
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
            
            $formatDate = function($date) {
                return $date instanceof Carbon 
                    ? $date->format('Y-m-d')
                    : Carbon::parse($date)->format('Y-m-d');
            };
            
            $fridayPrayerTime = Carbon::parse($formatDate($prayerTime->date) . ' ' . $getTimeString($prayerTime->ogle));
        }

        $now = Carbon::now();
        $settings = (object)[
            'association_name' => Settings::get('organization_name', 'Helmstedt Fatih Camii'),
            'association_logo' => Settings::get('logo'),
        ];

        return view('ezan', compact('prayerTime', 'orgName', 'nextPrayer', 'nextPrayerTime', 'timeUntilNext', 'now', 'settings', 'sabahPrayerTime', 'fridayPrayerTime'));
    }

    /**
     * Display events page with tabs
     */
    public function events(Request $request)
    {
        $tab = $request->get('tab', 'all'); // all, upcoming, past

        $query = Event::active();

        switch ($tab) {
            case 'upcoming':
                $events = $query->upcoming()->orderByEventDate()->paginate(9);
                break;
            case 'past':
                $events = $query->past()->orderBy('event_date', 'desc')->paginate(9);
                break;
            default:
                $events = $query->orderBy('event_date', 'desc')->paginate(9);
                break;
        }

        return view('events.index', compact('events', 'tab'));
    }
}
