<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventAdvertisement;
use App\Models\Event;
use Illuminate\Http\Request;

class EventAdvertisementController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $advertisements = EventAdvertisement::orderBy('created_at')->get();

        return view('admin.event-advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.event-advertisements.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->title, // name'i title'dan otomatik oluştur
            'title' => $request->title,
            'content' => $request->content,
            'footer_text' => 'YÖNETİM KURULU',
            'display_positions' => [], // Boş array - tüm pozisyonlarda göster
            'is_active' => $request->has('is_active'),
            'sort_order' => 0
        ];

        // Resim yükleme
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/event-ads'), $imageName);
            $data['image'] = 'uploads/event-ads/' . $imageName;
        }

        EventAdvertisement::create($data);

        return redirect()->route('admin.event-advertisements.index')
            ->with('success', 'Etkinlik reklamı başarıyla oluşturuldu.');
    }

    public function show(EventAdvertisement $eventAdvertisement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.event-advertisements.show', compact('eventAdvertisement'));
    }

    public function edit(EventAdvertisement $eventAdvertisement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.event-advertisements.edit', compact('eventAdvertisement'));
    }

    public function update(Request $request, EventAdvertisement $eventAdvertisement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_active' => 'boolean'
        ]);

        $data = [
            'name' => $request->title, // name'i title'dan otomatik oluştur
            'title' => $request->title,
            'content' => $request->content,
            'footer_text' => 'YÖNETİM KURULU',
            'display_positions' => [], // Boş array - tüm pozisyonlarda göster
            'is_active' => $request->has('is_active'),
            'sort_order' => 0
        ];

        // Resim yükleme
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($eventAdvertisement->image && file_exists(public_path($eventAdvertisement->image))) {
                unlink(public_path($eventAdvertisement->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/event-ads'), $imageName);
            $data['image'] = 'uploads/event-ads/' . $imageName;
        }

        $eventAdvertisement->update($data);

        return redirect()->route('admin.event-advertisements.index')
            ->with('success', 'Etkinlik reklamı başarıyla güncellendi.');
    }

    public function destroy(EventAdvertisement $eventAdvertisement)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        // Resmi sil
        if ($eventAdvertisement->image && file_exists(public_path($eventAdvertisement->image))) {
            unlink(public_path($eventAdvertisement->image));
        }

        $eventAdvertisement->delete();

        return redirect()->route('admin.event-advertisements.index')
            ->with('success', 'Etkinlik reklamı başarıyla silindi.');
    }
}
