<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuickAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuickAccessController extends Controller
{
    public function index()
    {
        // Authorization check
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $quickAccessItems = QuickAccess::orderBy('sort_order')->get();
        return view('admin.quick-access.index', compact('quickAccessItems'));
    }

    public function create()
    {
        // Authorization check
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.quick-access.create');
    }

    public function store(Request $request)
    {
        // Authorization check
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'icon' => 'required|string|max:100',
            'icon_color' => 'required|string|max:7',
            'url' => 'required|url|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        QuickAccess::create([
            'title_tr' => $request->title_tr,
            'title_de' => $request->title_de,
            'description_tr' => $request->description_tr ?? '',
            'description_de' => $request->description_de ?? '',
            'icon' => $request->icon,
            'icon_color' => $request->icon_color,
            'url' => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.quick-access.index')
            ->with('success', 'Hızlı erişim öğesi başarıyla oluşturuldu.');
    }

    public function edit(QuickAccess $quickAccess)
    {
        // Authorization check
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return view('admin.quick-access.edit', compact('quickAccess'));
    }

    public function update(Request $request, QuickAccess $quickAccess)
    {
        // Authorization check
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'description_tr' => 'nullable|string',
            'description_de' => 'nullable|string',
            'icon' => 'required|string|max:100',
            'icon_color' => 'required|string|max:7',
            'url' => 'required|url|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $quickAccess->update([
            'title_tr' => $request->title_tr,
            'title_de' => $request->title_de,
            'description_tr' => $request->description_tr ?? '',
            'description_de' => $request->description_de ?? '',
            'icon' => $request->icon,
            'icon_color' => $request->icon_color,
            'url' => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.quick-access.index')
            ->with('success', 'Hızlı erişim öğesi başarıyla güncellendi.');
    }

    public function destroy(QuickAccess $quickAccess)
    {
        // Authorization check
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $quickAccess->delete();
        return redirect()->route('admin.quick-access.index')
            ->with('success', 'Hızlı erişim öğesi başarıyla silindi.');
    }

    public function toggleStatus(QuickAccess $quickAccess)
    {
        // Authorization check
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu işlem için yetkiniz yok.');
        }

        $quickAccess->update(['is_active' => !$quickAccess->is_active]);

        $status = $quickAccess->is_active ? 'aktif' : 'pasif';
        return redirect()->back()->with('success', "Hızlı erişim öğesi {$status} yapıldı.");
    }
}
