<?php

namespace App\Http\Controllers;

use App\Models\VideoGallery;
use App\Models\VideoCategory;
use Illuminate\Http\Request;

class VideoGalleryController extends Controller
{
    public function index()
    {
        $categories = VideoCategory::active()->withCount('activeVideos')->ordered()->get();
        $orgName = \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi');

        return view('video-gallery.index', compact('categories', 'orgName'));
    }

    public function category($slug)
    {
        $videoCategory = VideoCategory::where('slug', $slug)->firstOrFail();
        $videos = $videoCategory->activeVideos()->ordered()->get();
        $orgName = \App\Models\Settings::get('organization_name', 'Cami Üyelik Sistemi');

        return view('video-gallery.category', compact('videoCategory', 'videos', 'orgName'));
    }
}
