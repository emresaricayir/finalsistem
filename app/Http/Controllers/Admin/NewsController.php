<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function __construct()
    {
        // Middleware will be handled in individual methods
    }

    public function index(Request $request)
    {
        $query = News::query();

        if ($request->filled('search')) {
            $searchTerm = $request->string('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title_tr', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('title_de', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('content_tr', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('content_de', 'LIKE', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $newsItems = $query->orderBy('sort_order')
                           ->orderBy('created_at', 'desc')
                           ->paginate(15)
                           ->appends($request->query());

        return view('admin.news.index', compact('newsItems'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'content_tr' => 'nullable|string',
            'content_de' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'photos.*' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['created_by'] = Auth::id();

        // Handle cover image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        $news = News::create($validated);

        // Optional multiple photos
        if ($request->hasFile('photos')) {
            $currentOrder = -1;
            foreach ($request->file('photos') as $file) {
                $p = $file->store('news/photos', 'public');
                NewsPhoto::create([
                    'news_id' => $news->id,
                    'image_path' => 'storage/' . $p,
                    'sort_order' => ++$currentOrder,
                    'created_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('admin.news.edit', $news)
            ->with('success', 'Haber başarıyla oluşturuldu.');
    }

    public function edit(News $news)
    {
        $news->load('photos');
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'content_tr' => 'nullable|string',
            'content_de' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'photos.*' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        $news->update($validated);

        // Handle additional photos upload
        if ($request->hasFile('photos')) {
            $currentOrder = $news->photos()->count();
            foreach ($request->file('photos') as $file) {
                $p = $file->store('news/photos', 'public');
                NewsPhoto::create([
                    'news_id' => $news->id,
                    'image_path' => 'storage/' . $p,
                    'sort_order' => ++$currentOrder,
                    'created_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('admin.news.edit', $news)
            ->with('success', 'Haber başarıyla güncellendi.');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')
            ->with('success', 'Haber başarıyla silindi.');
    }

    public function toggleStatus(News $news)
    {
        $news->update(['is_active' => !$news->is_active]);
        $status = $news->is_active ? 'aktif' : 'pasif';
        return back()->with('success', "Haber {$status} duruma getirildi.");
    }

    public function destroyPhoto(News $news, NewsPhoto $photo)
    {
        // Verify that this photo belongs to this news
        if ($photo->news_id !== $news->id) {
            return back()->with('error', 'Geçersiz fotoğraf.');
        }

        // Delete the file from storage
        if ($photo->image_path && file_exists(public_path($photo->image_path))) {
            unlink(public_path($photo->image_path));
        }

        // Delete the database record
        $photo->delete();

        return back()->with('success', 'Fotoğraf başarıyla silindi.');
    }
}


