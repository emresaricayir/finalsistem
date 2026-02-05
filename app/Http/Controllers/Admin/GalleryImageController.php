<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryImageController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $query = GalleryImage::with('category');

        if ($request->filled('category_id')) {
            $query->where('gallery_category_id', $request->category_id);
        }

        $images = $query->orderBy('sort_order')->paginate(20);
        $categories = GalleryCategory::active()->ordered()->get();

        return view('admin.gallery.images.index', compact('images', 'categories'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $categories = GalleryCategory::active()->ordered()->get();
        $selectedCategory = $request->get('category_id');

        return view('admin.gallery.images.create', compact('categories', 'selectedCategory'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $path = $request->file('image')->store('gallery/images', 'public');

        GalleryImage::create([
            'title_tr' => $request->title_tr,
            'title_de' => $request->title_de,
            'description_tr' => $request->description_tr,
            'description_de' => $request->description_de,
            'image_path' => $path,
            'gallery_category_id' => $request->gallery_category_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.gallery-images.index')->with('success', 'Görsel eklendi.');
    }

    public function show(GalleryImage $galleryImage)
    {
        $galleryImage->load('category');
        return view('admin.gallery.images.show', compact('galleryImage'));
    }

    public function edit(GalleryImage $galleryImage)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $categories = GalleryCategory::active()->ordered()->get();
        return view('admin.gallery.images.edit', compact('galleryImage', 'categories'));
    }

    public function update(Request $request, GalleryImage $galleryImage)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = [
            'title_tr' => $request->title_tr,
            'title_de' => $request->title_de,
            'description_tr' => $request->description_tr,
            'description_de' => $request->description_de,
            'gallery_category_id' => $request->gallery_category_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ];

        if ($request->hasFile('image')) {
            if ($galleryImage->image_path) {
                Storage::disk('public')->delete($galleryImage->image_path);
            }
            $data['image_path'] = $request->file('image')->store('gallery/images', 'public');
        }

        $galleryImage->update($data);

        return redirect()->route('admin.gallery-images.index')->with('success', 'Görsel güncellendi.');
    }

    public function destroy(GalleryImage $galleryImage)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        if ($galleryImage->image_path) {
            Storage::disk('public')->delete($galleryImage->image_path);
        }

        $galleryImage->delete();
        return redirect()->route('admin.gallery-images.index')->with('success', 'Görsel silindi.');
    }

    public function toggleStatus(GalleryImage $galleryImage)
    {
        $galleryImage->update(['is_active' => !$galleryImage->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $galleryImage->is_active
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:gallery_images,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->items as $item) {
            GalleryImage::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $uploaded = 0;
        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('gallery/images', 'public');

            GalleryImage::create([
                'gallery_category_id' => $request->gallery_category_id,
                'title_tr' => 'Resim ' . ($index + 1),
                'title_de' => 'Bild ' . ($index + 1),
                'image_path' => $path,
                'sort_order' => $index,
                'is_active' => true
            ]);

            $uploaded++;
        }

        return redirect()->route('admin.gallery-images.index', ['category_id' => $request->gallery_category_id])
            ->with('success', $uploaded . ' resim başarıyla yüklendi.');
    }
}
