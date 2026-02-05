<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAdvertisement;
use Illuminate\Http\Request;

class EventDisplayController extends Controller
{
    public function index()
    {
        $events = Event::active()
            ->upcoming()
            ->orderByEventDate()
            ->get();

        // Add event type to each event for JavaScript
        $events->each(function ($event) {
            $event->event_type_for_js = $event->event_type;
        });

        // Get active advertisements
        $advertisements = EventAdvertisement::getActiveAds();

        // Karışık içerik oluştur (etkinlik + reklam)
        $mixedContent = [];
        
        if ($events->count() > 0 && $advertisements->count() > 0) {
            // Etkinlik varsa: 1 etkinlik, 1 reklam sıralaması
            $eventIndex = 0;
            $adIndex = 0;
            $maxItems = max($events->count() * 2, $advertisements->count() * 2); // Yeterli döngü
            
            for ($i = 0; $i < $maxItems; $i++) {
                if ($i % 2 == 0) {
                    // Çift sıralarda etkinlik
                    if ($eventIndex < $events->count()) {
                        $mixedContent[] = [
                            'type' => 'event',
                            'data' => $events[$eventIndex],
                            'duration' => $events[$eventIndex]->display_duration * 1000
                        ];
                        $eventIndex++;
                    }
                } else {
                    // Tek sıralarda reklam
                    if ($adIndex < $advertisements->count()) {
                        $mixedContent[] = [
                            'type' => 'advertisement',
                            'data' => $advertisements[$adIndex],
                            'duration' => 10000
                        ];
                        $adIndex++;
                    } else {
                        // Reklam bittiyse başa dön
                        $adIndex = 0;
                        if ($advertisements->count() > 0) {
                            $mixedContent[] = [
                                'type' => 'advertisement',
                                'data' => $advertisements[$adIndex],
                                'duration' => 10000
                            ];
                            $adIndex++;
                        }
                    }
                }
            }
        } elseif ($advertisements->count() > 0) {
            // Sadece reklamlar varsa
            foreach ($advertisements as $ad) {
                $mixedContent[] = [
                    'type' => 'advertisement',
                    'data' => $ad,
                    'duration' => 10000
                ];
            }
        } elseif ($events->count() > 0) {
            // Sadece etkinlikler varsa
            foreach ($events as $event) {
                $mixedContent[] = [
                    'type' => 'event',
                    'data' => $event,
                    'duration' => $event->display_duration * 1000
                ];
            }
        }

        $organizationName = \App\Models\Settings::get('organization_name', '... derneği');

        return view('events.display', compact('events', 'advertisements', 'mixedContent', 'organizationName'));
    }

    public function api()
    {
        $events = Event::active()
            ->upcoming()
            ->orderByEventDate()
            ->get();

        $advertisements = EventAdvertisement::getActiveAds();

        // Karışık içerik oluştur (API için)
        $mixedContent = [];
        
        if ($events->count() > 0 && $advertisements->count() > 0) {
            // Etkinlik varsa: 1 etkinlik, 1 reklam sıralaması
            $eventIndex = 0;
            $adIndex = 0;
            $maxItems = max($events->count() * 2, $advertisements->count() * 2);
            
            for ($i = 0; $i < $maxItems; $i++) {
                if ($i % 2 == 0) {
                    // Çift sıralarda etkinlik
                    if ($eventIndex < $events->count()) {
                        $event = $events[$eventIndex];
                        $mixedContent[] = [
                            'type' => 'event',
                            'id' => $event->id,
                            'title' => $event->title,
                            'description' => $event->description,
                            'event_date' => $event->event_date->toISOString(),
                            'event_type' => $event->event_type,
                            'location' => $event->location,
                            'display_duration' => $event->display_duration,
                            'duration' => $event->display_duration * 1000
                        ];
                        $eventIndex++;
                    }
                } else {
                    // Tek sıralarda reklam
                    if ($adIndex < $advertisements->count()) {
                        $ad = $advertisements[$adIndex];
                        $mixedContent[] = [
                            'type' => 'advertisement',
                            'id' => $ad->id,
                            'title' => $ad->title,
                            'content' => $ad->content,
                            'image' => $ad->image ? asset($ad->image) : null,
                            'footer_text' => $ad->footer_text,
                            'duration' => 10000
                        ];
                        $adIndex++;
                    } else {
                        // Reklam bittiyse başa dön
                        $adIndex = 0;
                        if ($advertisements->count() > 0) {
                            $ad = $advertisements[$adIndex];
                            $mixedContent[] = [
                                'type' => 'advertisement',
                                'id' => $ad->id,
                                'title' => $ad->title,
                                'content' => $ad->content,
                                'image' => $ad->image ? asset($ad->image) : null,
                                'footer_text' => $ad->footer_text,
                                'duration' => 10000
                            ];
                            $adIndex++;
                        }
                    }
                }
            }
        } elseif ($advertisements->count() > 0) {
            // Sadece reklamlar varsa
            foreach ($advertisements as $ad) {
                $mixedContent[] = [
                    'type' => 'advertisement',
                    'id' => $ad->id,
                    'title' => $ad->title,
                    'content' => $ad->content,
                    'image' => $ad->image ? asset($ad->image) : null,
                    'footer_text' => $ad->footer_text,
                    'duration' => 10000
                ];
            }
        } elseif ($events->count() > 0) {
            // Sadece etkinlikler varsa
            foreach ($events as $event) {
                $mixedContent[] = [
                    'type' => 'event',
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'event_date' => $event->event_date->toISOString(),
                    'event_type' => $event->event_type,
                    'location' => $event->location,
                    'display_duration' => $event->display_duration,
                    'duration' => $event->display_duration * 1000
                ];
            }
        }

        return response()->json([
            'mixedContent' => $mixedContent
        ]);
    }
}
