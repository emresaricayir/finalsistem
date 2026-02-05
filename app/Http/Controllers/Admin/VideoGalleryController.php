<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoGallery;
use App\Models\VideoCategory;
use Illuminate\Http\Request;

class VideoGalleryController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $query = VideoGallery::with('category');

        if ($request->filled('category_id')) {
            $query->where('video_category_id', $request->category_id);
        }

        $videos = $query->ordered()->paginate(10);
        $categories = VideoCategory::active()->ordered()->withCount('videos')->get();

        return view('admin.video-gallery.index', compact('videos', 'categories'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $categories = VideoCategory::active()->ordered()->get();
        $selectedCategory = $request->get('category_id');

        return view('admin.video-gallery.create', compact('categories', 'selectedCategory'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'youtube_url' => 'required|url|max:2000',
            'thumbnail_url' => 'nullable|url|max:2000',
            'video_category_id' => 'required|exists:video_categories,id',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $youtubeId = VideoGallery::extractYouTubeId($validated['youtube_url']);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'Geçerli bir YouTube bağlantısı giriniz.'])->withInput();
        }

        $thumbnail = $validated['thumbnail_url'] ?? "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";

        VideoGallery::create([
            'title_tr' => $validated['title_tr'],
            'title_de' => $validated['title_de'] ?? null,
            'description_tr' => $validated['description_tr'] ?? null,
            'description_de' => $validated['description_de'] ?? null,
            'youtube_url' => $validated['youtube_url'],
            'youtube_id' => $youtubeId,
            'thumbnail_url' => $thumbnail,
            'video_category_id' => $validated['video_category_id'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.video-gallery.index')->with('success', 'Video eklendi.');
    }

    public function edit(VideoGallery $videoGallery)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $categories = VideoCategory::active()->ordered()->get();
        return view('admin.video-gallery.edit', compact('videoGallery', 'categories'));
    }

    public function update(Request $request, VideoGallery $videoGallery)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'youtube_url' => 'required|url|max:2000',
            'thumbnail_url' => 'nullable|url|max:2000',
            'video_category_id' => 'required|exists:video_categories,id',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $youtubeId = VideoGallery::extractYouTubeId($validated['youtube_url']);
        if (!$youtubeId) {
            return back()->withErrors(['youtube_url' => 'Geçerli bir YouTube bağlantısı giriniz.'])->withInput();
        }

        $thumbnail = $validated['thumbnail_url'] ?? "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg";

        $videoGallery->update([
            'title_tr' => $validated['title_tr'],
            'title_de' => $validated['title_de'] ?? null,
            'description_tr' => $validated['description_tr'] ?? null,
            'description_de' => $validated['description_de'] ?? null,
            'youtube_url' => $validated['youtube_url'],
            'youtube_id' => $youtubeId,
            'thumbnail_url' => $thumbnail,
            'video_category_id' => $validated['video_category_id'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.video-gallery.index')->with('success', 'Video güncellendi.');
    }

    public function destroy(VideoGallery $videoGallery)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $videoGallery->delete();
        return redirect()->route('admin.video-gallery.index')->with('success', 'Video silindi.');
    }
}
