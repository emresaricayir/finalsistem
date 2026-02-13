<?php

namespace App\Http\Controllers;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use App\Models\Settings;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
        $categories = GalleryCategory::active()
            ->ordered()
            ->withCount(['activeImages'])
            ->get();
            
        return view('gallery.index', compact('categories', 'orgName'));
    }
    
    public function category($slug)
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
        $category = GalleryCategory::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        $images = $category->activeImages()->paginate(16);
        // Tüm görselleri Fancybox için al (pagination olmadan)
        $allImages = $category->activeImages()->get();
        
        return view('gallery.category', compact('category', 'images', 'allImages', 'orgName'));
    }
    
    public function image($categorySlug, $imageId)
    {
        $orgName = Settings::get('organization_name', 'Cami Üyelik Sistemi');
        $category = GalleryCategory::where('slug', $categorySlug)
            ->where('is_active', true)
            ->firstOrFail();
            
        $image = GalleryImage::where('id', $imageId)
            ->where('gallery_category_id', $category->id)
            ->where('is_active', true)
            ->firstOrFail();
            
        // Get previous and next images in the same category
        $previousImage = GalleryImage::where('gallery_category_id', $category->id)
            ->where('is_active', true)
            ->where('sort_order', '<', $image->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();
            
        $nextImage = GalleryImage::where('gallery_category_id', $category->id)
            ->where('is_active', true)
            ->where('sort_order', '>', $image->sort_order)
            ->orderBy('sort_order', 'asc')
            ->first();
            
        return view('gallery.image', compact('category', 'image', 'previousImage', 'nextImage', 'orgName'));
    }
}
