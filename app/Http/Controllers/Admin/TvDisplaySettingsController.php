<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TvDisplaySettings;
use Illuminate\Http\Request;

class TvDisplaySettingsController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $settings = TvDisplaySettings::getCurrentSettings();

        return view('admin.settings.tv-display-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'page_transition_speed' => 'required|integer|min:1|max:60',
            'show_dues' => 'boolean',
            'auto_refresh_enabled' => 'boolean',
            'auto_refresh_interval' => 'required|integer|min:5|max:300',
            'member_display_limit' => 'required|integer|min:4|max:32',
            'default_year' => 'required|integer|min:2026|max:2030'
        ]);

        $settings = TvDisplaySettings::getCurrentSettings();

        $settings->update([
            'page_transition_speed' => $request->page_transition_speed,
            'show_dues' => $request->has('show_dues'),
            'auto_refresh_enabled' => $request->has('auto_refresh_enabled'),
            'auto_refresh_interval' => $request->auto_refresh_interval,
            'member_display_limit' => $request->member_display_limit,
            'default_year' => $request->default_year
        ]);

        return redirect()->route('admin.settings.tv-display-settings.index')
            ->with('success', 'TV ayarları başarıyla güncellendi.');
    }

    public function reset()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $settings = TvDisplaySettings::getCurrentSettings();
        $defaultSettings = TvDisplaySettings::getDefaultSettings();

        $settings->update($defaultSettings);

        return redirect()->route('admin.settings.tv-display-settings.index')
            ->with('success', 'TV ayarları varsayılan değerlere sıfırlandı.');
    }
}
