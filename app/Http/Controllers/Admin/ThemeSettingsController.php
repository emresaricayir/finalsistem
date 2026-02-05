<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class ThemeSettingsController extends Controller
{
    /**
     * Tema ayarları sayfasını göster
     */
    public function index()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        // Mevcut tema ayarlarını al
        $themeSettings = [
            'primary_color' => Settings::get('theme_primary_color', '#085952'),
            'secondary_color' => Settings::get('theme_secondary_color', '#0a7b73'),
            'gradient_start' => Settings::get('theme_gradient_start', '#076961'),
            'gradient_end' => Settings::get('theme_gradient_end', '#0a7b6e'),
            'gradient_direction' => Settings::get('theme_gradient_direction', 'to right'),
            'hover_color' => Settings::get('theme_hover_color', '#0f766e'),
            'button_color' => Settings::get('theme_button_color', '#0d9488'),
            'link_color' => Settings::get('theme_link_color', '#0d9488'),
            'use_gradient' => Settings::get('theme_use_gradient', '1'),
        ];

        return view('admin.theme-settings.index', compact('themeSettings'));
    }

    /**
     * Tema ayarlarını güncelle
     */
    public function update(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'gradient_start' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'gradient_end' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'gradient_direction' => 'required|string|in:to right,to bottom,to left,to top,to bottom right,to bottom left,to top right,to top left',
            'hover_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'button_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'link_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'use_gradient' => 'nullable|boolean',
        ]);

        // Tema ayarlarını kaydet
        Settings::set('theme_primary_color', $request->primary_color);
        Settings::set('theme_secondary_color', $request->secondary_color);
        Settings::set('theme_gradient_start', $request->gradient_start);
        Settings::set('theme_gradient_end', $request->gradient_end);
        Settings::set('theme_gradient_direction', $request->gradient_direction);
        Settings::set('theme_hover_color', $request->hover_color);
        Settings::set('theme_button_color', $request->button_color);
        Settings::set('theme_link_color', $request->link_color);
        Settings::set('theme_use_gradient', $request->has('use_gradient') ? '1' : '0');

        return redirect()->route('admin.theme-settings.index')
            ->with('success', 'Tema ayarları başarıyla güncellendi!');
    }
}
