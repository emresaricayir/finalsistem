<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TvDisplayMessage;
use Illuminate\Http\Request;

class TvDisplayMessageController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $messages = TvDisplayMessage::orderBy('created_at')->get();

        return view('admin.settings.tv-display-messages.index', compact('messages'));
    }

    public function create()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Toplam üye sayısını al ve sayfa sayısını hesapla
        $totalMembers = \App\Models\Member::where('status', 'active')->count();
        $perPage = 16; // Her sayfada 16 üye
        $totalPages = ceil($totalMembers / $perPage);

        // Tüm sayfaları oluştur (1'den toplam sayfa sayısına kadar)
        $availablePages = [];
        for ($i = 1; $i <= $totalPages; $i++) {
            $availablePages[] = $i;
        }

        // Dolu sayfaları bul (zaten reklam eklenmiş sayfalar)
        $occupiedPages = TvDisplayMessage::where('is_active', true)
            ->get()
            ->pluck('display_pages')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        return view('admin.settings.tv-display-messages.create', compact('totalPages', 'availablePages', 'occupiedPages'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'footer_text' => 'nullable|string|max:255',
            'display_pages' => 'nullable|array',
            'display_pages.*' => 'integer|min:1|max:100',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->title, // name'i title'dan otomatik oluştur
            'title' => $request->title,
            'content' => $request->content,
            'footer_text' => $request->footer_text ?? 'YÖNETİM KURULU',
            'display_pages' => $request->display_pages ? array_map('intval', $request->display_pages) : [],
            'is_active' => $request->has('is_active'),
        ];

        // Resim yükleme
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/tv-messages'), $imageName);
            $data['image'] = 'uploads/tv-messages/' . $imageName;
        }

        TvDisplayMessage::create($data);

        return redirect()->route('admin.settings.tv-display-messages.index')
            ->with('success', 'Reklam başarıyla oluşturuldu.');
    }

    public function show(TvDisplayMessage $tvDisplayMessage)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.settings.tv-display-messages.show', compact('tvDisplayMessage'));
    }

    public function edit(TvDisplayMessage $tvDisplayMessage)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Toplam üye sayısını al ve sayfa sayısını hesapla
        $totalMembers = \App\Models\Member::where('status', 'active')->count();
        $perPage = 16; // Her sayfada 16 üye
        $totalPages = ceil($totalMembers / $perPage);

        // Tüm sayfaları oluştur (1'den toplam sayfa sayısına kadar)
        $availablePages = [];
        for ($i = 1; $i <= $totalPages; $i++) {
            $availablePages[] = $i;
        }

        // Dolu sayfaları bul (zaten reklam eklenmiş sayfalar) - mevcut reklam hariç
        $occupiedPages = TvDisplayMessage::where('is_active', true)
            ->where('id', '!=', $tvDisplayMessage->id)
            ->get()
            ->pluck('display_pages')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        return view('admin.settings.tv-display-messages.edit', compact('tvDisplayMessage', 'totalPages', 'availablePages', 'occupiedPages'));
    }

    public function update(Request $request, TvDisplayMessage $tvDisplayMessage)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'footer_text' => 'nullable|string|max:255',
            'display_pages' => 'nullable|array',
            'display_pages.*' => 'integer|min:1|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $data = [
            'name' => $request->title, // name'i title'dan otomatik oluştur
            'title' => $request->title,
            'content' => $request->content,
            'footer_text' => $request->footer_text ?? 'YÖNETİM KURULU',
            'display_pages' => $request->display_pages ? array_map('intval', $request->display_pages) : [],
            'is_active' => $request->has('is_active'),
        ];

        // Resim yükleme
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($tvDisplayMessage->image && file_exists(public_path($tvDisplayMessage->image))) {
                unlink(public_path($tvDisplayMessage->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/tv-messages'), $imageName);
            $data['image'] = 'uploads/tv-messages/' . $imageName;
        }

        $tvDisplayMessage->update($data);

        return redirect()->route('admin.settings.tv-display-messages.index')
            ->with('success', 'Reklam başarıyla güncellendi.');
    }

    public function destroy(TvDisplayMessage $tvDisplayMessage)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $tvDisplayMessage->delete();

        return redirect()->route('admin.settings.tv-display-messages.index')
            ->with('success', 'Reklam başarıyla silindi.');
    }
}
