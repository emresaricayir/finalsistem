<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonnelCategory;
use App\Models\BoardMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PersonnelCategoryController extends Controller
{
    public function index()
    {
        $categories = PersonnelCategory::withCount('personnel')->ordered()->get();
        return view('admin.personnel-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.personnel-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_tr' => 'required|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'color' => 'required|string|max:7',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        PersonnelCategory::create([
            'name_tr' => $request->name_tr,
            'name_de' => $request->name_de ?? $request->name_tr,
            'description_tr' => $request->description_tr,
            'description_de' => $request->description_de ?? $request->description_tr,
            'color' => $request->color,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        Cache::forget('personnel_categories');

        return redirect()->route('admin.personnel-categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    public function show(PersonnelCategory $personnelCategory)
    {
        $personnel = $personnelCategory->personnel()->ordered()->get();
        return view('admin.personnel-categories.show', compact('personnelCategory', 'personnel'));
    }

    public function edit(PersonnelCategory $personnelCategory)
    {
        return view('admin.personnel-categories.edit', compact('personnelCategory'));
    }

    public function update(Request $request, PersonnelCategory $personnelCategory)
    {
        $request->validate([
            'name_tr' => 'required|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'color' => 'required|string|max:7',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $personnelCategory->update([
            'name_tr' => $request->name_tr,
            'name_de' => $request->name_de ?? $request->name_tr,
            'description_tr' => $request->description_tr,
            'description_de' => $request->description_de ?? $request->description_tr,
            'color' => $request->color,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        Cache::forget('personnel_categories');

        return redirect()->route('admin.personnel-categories.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy(PersonnelCategory $personnelCategory)
    {
        // Check if category has personnel
        if ($personnelCategory->personnel()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Bu kategoriye ait personeller bulunduğu için silinemez. Önce personelleri başka kategoriye taşıyın.');
        }

        $personnelCategory->delete();
        Cache::forget('personnel_categories');

        return redirect()->route('admin.personnel-categories.index')
            ->with('success', 'Kategori başarıyla silindi.');
    }

    public function toggleStatus(PersonnelCategory $personnelCategory)
    {
        $personnelCategory->update(['is_active' => !$personnelCategory->is_active]);
        Cache::forget('personnel_categories');

        return response()->json([
            'success' => true,
            'is_active' => $personnelCategory->is_active
        ]);
    }
}
