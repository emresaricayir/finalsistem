<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsPhotoController extends Controller
{
    public function store(Request $request, News $news)
    {
        $request->validate([
            'photo' => 'required|image|max:5120', // 5MB
            'caption' => 'nullable|string|max:255',
        ]);

        // Store on public disk to storage/app/public/news/photos
        $path = $request->file('photo')->store('news/photos', 'public');

        NewsPhoto::create([
            'news_id' => $news->id,
            // Use Storage::url to generate web-accessible path (handles windows slashes)
            'image_path' => Storage::url($path), // "/storage/news/photos/xxx.jpg"
            'caption' => $request->caption,
            'sort_order' => ($news->photos()->max('sort_order') ?? -1) + 1,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Fotoğraf eklendi.');
    }

    public function destroy(News $news, NewsPhoto $photo)
    {
        // Convert public URL ("/storage/") back to disk path ("public/") safely
        $publicUrl = ltrim((string) $photo->image_path, '/');
        $diskPath = preg_replace('#^storage/#', 'public/', $publicUrl);

        if ($diskPath && Storage::exists($diskPath)) {
            Storage::delete($diskPath);
        }

        $photo->delete();
        return back()->with('success', 'Fotoğraf silindi.');
    }
}
