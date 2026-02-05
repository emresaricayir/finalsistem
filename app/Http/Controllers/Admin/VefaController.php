<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VefaController extends Controller
{
    public function index()
    {
        $vefas = Vefa::orderBySort()->paginate(10);
        return view('admin.vefas.index', compact('vefas'));
    }

    public function create()
    {
        return view('admin.vefas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'display_duration' => 'required|integer|min:1|max:60',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
            'hometown' => 'nullable|string|max:255',
            'burial_place' => 'nullable|string|max:255'
        ]);

        $imagePath = $request->file('image')->store('vefas', 'public');

        Vefa::create([
            'title' => $request->title,
            'image_path' => $imagePath,
            'display_duration' => $request->display_duration,
            'sort_order' => $request->sort_order,
            'is_active' => $request->has('is_active'),
            'birth_date' => $request->birth_date,
            'death_date' => $request->death_date,
            'hometown' => $request->hometown,
            'burial_place' => $request->burial_place
        ]);

        return redirect()->route('admin.vefas.index')->with('success', 'Vefa fotoğrafı başarıyla eklendi.');
    }

    public function show(Vefa $vefa)
    {
        return view('admin.vefas.show', compact('vefa'));
    }

    public function edit(Vefa $vefa)
    {
        return view('admin.vefas.edit', compact('vefa'));
    }

    public function update(Request $request, Vefa $vefa)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'display_duration' => 'required|integer|min:1|max:60',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'birth_date' => 'nullable|date',
            'death_date' => 'nullable|date',
            'hometown' => 'nullable|string|max:255',
            'burial_place' => 'nullable|string|max:255'
        ]);

        $data = [
            'title' => $request->title,
            'display_duration' => $request->display_duration,
            'sort_order' => $request->sort_order,
            'is_active' => $request->has('is_active'),
            'birth_date' => $request->birth_date,
            'death_date' => $request->death_date,
            'hometown' => $request->hometown,
            'burial_place' => $request->burial_place
        ];

        if ($request->hasFile('image')) {
            // Eski resmi sil
            Storage::disk('public')->delete($vefa->image_path);
            // Yeni resmi kaydet
            $data['image_path'] = $request->file('image')->store('vefas', 'public');
        }

        $vefa->update($data);

        return redirect()->route('admin.vefas.index')->with('success', 'Vefa fotoğrafı başarıyla güncellendi.');
    }

    public function destroy(Vefa $vefa)
    {
        // Resmi sil
        Storage::disk('public')->delete($vefa->image_path);

        $vefa->delete();

        return redirect()->route('admin.vefas.index')->with('success', 'Vefa fotoğrafı başarıyla silindi.');
    }
}
