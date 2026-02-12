<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $query = Announcement::with('creator');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title_tr', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('title_de', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('content_tr', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('content_de', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $announcements = $query->orderBy('sort_order')
                              ->orderBy('created_at', 'desc')
                              ->paginate(15)
                              ->appends($request->query());

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.announcements.create');
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
            'content_tr' => 'nullable|string',
            'content_de' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_de' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $validated;
        $data['type'] = 'general'; // Varsayılan olarak genel duyuru
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');
        $data['sort_order'] = $validated['sort_order'] ?? 0;
        $data['created_by'] = Auth::id();

        // Handle cover image upload (Türkçe)
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('announcements', 'public');
        }

        // Handle cover image upload (Almanca)
        if ($request->hasFile('image_de')) {
            $data['image_path_de'] = $request->file('image_de')->store('announcements', 'public');
        }

        Announcement::create($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Duyuru başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $announcement->load('creator');
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'content_tr' => 'nullable|string',
            'content_de' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'image_de' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = $validated;
        $data['type'] = 'general'; // Varsayılan olarak genel duyuru
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');
        $data['sort_order'] = $validated['sort_order'] ?? 0;

        // Handle cover image upload (Türkçe)
        if ($request->hasFile('image')) {
            if ($announcement->getOriginal('image_path')) {
                Storage::disk('public')->delete($announcement->getOriginal('image_path'));
            }
            $data['image_path'] = $request->file('image')->store('announcements', 'public');
        }

        // Handle cover image upload (Almanca)
        if ($request->hasFile('image_de')) {
            if ($announcement->image_path_de) {
                Storage::disk('public')->delete($announcement->image_path_de);
            }
            $data['image_path_de'] = $request->file('image_de')->store('announcements', 'public');
        }

        $announcement->update($data);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Duyuru başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Delete the image if it exists
        if ($announcement->image_path && Storage::exists($announcement->image_path)) {
            Storage::delete($announcement->image_path);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Duyuru başarıyla silindi.');
    }

    /**
     * Toggle announcement status
     */
    public function toggleStatus(Announcement $announcement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $announcement->update([
            'is_active' => !$announcement->is_active
        ]);

        $status = $announcement->is_active ? 'aktif' : 'pasif';

        return redirect()->back()
            ->with('success', "Duyuru {$status} duruma getirildi.");
    }

    /**
     * Remove image from announcement
     */
    public function removeImage(Announcement $announcement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        if ($announcement->getOriginal('image_path') && Storage::disk('public')->exists($announcement->getOriginal('image_path'))) {
            Storage::disk('public')->delete($announcement->getOriginal('image_path'));
        }

        $announcement->update(['image_path' => null]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Türkçe kapak görseli silindi.');
    }

    /**
     * Remove German image from announcement
     */
    public function removeImageDe(Announcement $announcement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        if ($announcement->image_path_de && Storage::disk('public')->exists($announcement->image_path_de)) {
            Storage::disk('public')->delete($announcement->image_path_de);
        }

        $announcement->update(['image_path_de' => null]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Almanca kapak görseli silindi.');
    }

}
