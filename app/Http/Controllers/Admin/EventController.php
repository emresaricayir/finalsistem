<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
            'display_duration' => 'required|integer|min:1|max:60',
            'is_active' => 'boolean'
        ], [
            'image.image' => 'Yüklenen dosya bir görsel olmalıdır.',
            'image.mimes' => 'Görsel formatı jpeg, png, jpg, gif veya webp olmalıdır.',
            'image.max' => 'Görsel boyutu en fazla 6MB olabilir.',
        ]);

        $data = $request->only([
            'title_tr',
            'title_de',
            'description_tr',
            'description_de',
            'event_date',
            'location',
            'display_duration',
            'is_active',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
            $data['image_path'] = $imagePath;
        }

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Etkinlik başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
            'display_duration' => 'required|integer|min:1|max:60',
            'is_active' => 'boolean'
        ], [
            'image.image' => 'Yüklenen dosya bir görsel olmalıdır.',
            'image.mimes' => 'Görsel formatı jpeg, png, jpg, gif veya webp olmalıdır.',
            'image.max' => 'Görsel boyutu en fazla 6MB olabilir.',
        ]);

        $data = $request->only([
            'title_tr',
            'title_de',
            'description_tr',
            'description_de',
            'event_date',
            'location',
            'display_duration',
            'is_active',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image_path) {
                \Storage::disk('public')->delete($event->image_path);
            }
            $imagePath = $request->file('image')->store('events', 'public');
            $data['image_path'] = $imagePath;
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Etkinlik başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'editor'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Etkinlik başarıyla silindi.');
    }
}
