<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoCategoryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $categories = VideoCategory::withCount('videos')->orderBy('sort_order')->get();
        return view('admin.video-categories.index', compact('categories'));
    }

    public function create()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.video-categories.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'name_tr' => 'required|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:video_categories,slug',
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name_tr' => $request->name_tr,
            'name_de' => $request->name_de,
            'slug' => $request->slug ?: Str::slug($request->name_tr),
            'description_tr' => $request->description_tr,
            'description_de' => $request->description_de,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ];

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('video-categories', 'public');
            $data['cover_image'] = $path;
        }

        VideoCategory::create($data);

        return redirect()->route('admin.video-categories.index')
            ->with('success', 'Video kategorisi başarıyla oluşturuldu.');
    }

    public function show(VideoCategory $videoCategory)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.video-categories.show', compact('videoCategory'));
    }

    public function edit(VideoCategory $videoCategory)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.video-categories.edit', compact('videoCategory'));
    }

    public function update(Request $request, VideoCategory $videoCategory)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'name_tr' => 'required|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:video_categories,slug,' . $videoCategory->id,
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name_tr' => $request->name_tr,
            'name_de' => $request->name_de,
            'slug' => $request->slug ?: Str::slug($request->name_tr),
            'description_tr' => $request->description_tr,
            'description_de' => $request->description_de,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ];

        if ($request->hasFile('cover_image')) {
            if ($videoCategory->cover_image) {
                Storage::disk('public')->delete($videoCategory->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('video-categories', 'public');
        }

        $videoCategory->update($data);

        return redirect()->route('admin.video-categories.index')
            ->with('success', 'Video kategorisi başarıyla güncellendi.');
    }

    public function destroy(VideoCategory $videoCategory)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        if ($videoCategory->cover_image) {
            Storage::disk('public')->delete($videoCategory->cover_image);
        }

        $videoCategory->delete();
        return redirect()->route('admin.video-categories.index')->with('success', 'Video kategorisi silindi.');
    }

    public function toggleStatus(VideoCategory $videoCategory)
    {
        $videoCategory->update(['is_active' => !$videoCategory->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $videoCategory->is_active,
            'message' => 'Kategori durumu güncellendi.'
        ]);
    }
}
