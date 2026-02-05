<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $pages = Page::orderBy('sort_order', 'asc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages',
            'content_tr' => 'nullable|string',
            'content_de' => 'nullable|string',
            'meta_description_tr' => 'nullable|string|max:500',
            'meta_description_de' => 'nullable|string|max:500',
            'meta_keywords_tr' => 'nullable|string|max:500',
            'meta_keywords_de' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title_tr']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Sayfa başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('pages')->ignore($page->id)
            ],
            'content_tr' => 'nullable|string',
            'content_de' => 'nullable|string',
            'meta_description_tr' => 'nullable|string|max:500',
            'meta_description_de' => 'nullable|string|max:500',
            'meta_keywords_tr' => 'nullable|string|max:500',
            'meta_keywords_de' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title_tr']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Sayfa başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Sayfa başarıyla silindi.');
    }

    /**
     * Toggle the active status of a page.
     */
    public function toggleStatus(Page $page)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $page->update(['is_active' => !$page->is_active]);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Sayfa durumu güncellendi.');
    }

    /**
     * Update the sort order of pages.
     */
    public function reorder(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'pages' => 'required|array',
            'pages.*.id' => 'required|exists:pages,id',
            'pages.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->pages as $pageData) {
            Page::where('id', $pageData['id'])
                ->update(['sort_order' => $pageData['sort_order']]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Upload image for TinyMCE editor
     */
    public function uploadImage(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Bu sayfaya erişim yetkiniz yok.'
            ], 403);
        }

        // Log the request for debugging
        \Log::info('Page image upload attempt', [
            'has_file' => $request->hasFile('file'),
            'files' => $request->allFiles(),
            'content_type' => $request->header('content-type'),
        ]);

        try {
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            $file = $request->file('file');

            // Additional file checks
            if (!$file->isValid()) {
                throw new \Exception('Geçersiz dosya yüklendi.');
            }

            // Clean filename to avoid URL encoding issues
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $cleanName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
            $filename = time() . '_' . $cleanName . '.' . $extension;

            $path = $file->storeAs('page-images', $filename, 'public');

            $url = asset('storage/' . $path);

            \Log::info('Page image uploaded successfully', [
                'filename' => $filename,
                'path' => $path,
                'url' => $url
            ]);

            return response()->json([
                'success' => true,
                'url' => $url
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Page image upload validation error', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Dosya doğrulama hatası: ' . implode(', ', $e->errors()['file'] ?? ['Bilinmeyen hata'])
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Page image upload error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Resim yükleme hatası: ' . $e->getMessage()
            ], 500);
        }
    }
}

