<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryCategoryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $categories = GalleryCategory::withCount('images')->orderBy('sort_order')->get();
        return view('admin.gallery.categories.index', compact('categories'));
    }

    public function create()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.gallery.categories.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'name_tr' => 'required|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_categories,slug',
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
            $path = $request->file('cover_image')->store('gallery/categories', 'public');
            $data['cover_image'] = $path;
        }

        GalleryCategory::create($data);

        return redirect()->route('admin.gallery-categories.index')
            ->with('success', 'Galeri kategorisi başarıyla oluşturuldu.');
    }

    public function show(GalleryCategory $galleryCategory)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $galleryCategory->load(['images' => function($query) {
            $query->orderBy('sort_order');
        }]);

        return view('admin.gallery.categories.show', compact('galleryCategory'));
    }

    public function edit(GalleryCategory $galleryCategory)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.gallery.categories.edit', compact('galleryCategory'));
    }

    public function update(Request $request, GalleryCategory $galleryCategory)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'name_tr' => 'required|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:gallery_categories,slug,' . $galleryCategory->id,
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
            // Delete old image
            if ($galleryCategory->cover_image) {
                Storage::disk('public')->delete($galleryCategory->cover_image);
            }

            $path = $request->file('cover_image')->store('gallery/categories', 'public');
            $data['cover_image'] = $path;
        }

        $galleryCategory->update($data);

        return redirect()->route('admin.gallery-categories.index')
            ->with('success', 'Galeri kategorisi başarıyla güncellendi.');
    }

    public function destroy(GalleryCategory $galleryCategory)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Delete cover image
        if ($galleryCategory->cover_image) {
            Storage::disk('public')->delete($galleryCategory->cover_image);
        }

        $galleryCategory->delete();

        return redirect()->route('admin.gallery-categories.index')
            ->with('success', 'Galeri kategorisi başarıyla silindi.');
    }

    public function toggleStatus(GalleryCategory $galleryCategory)
    {
        $galleryCategory->update(['is_active' => !$galleryCategory->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $galleryCategory->is_active
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:gallery_categories,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->items as $item) {
            GalleryCategory::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
