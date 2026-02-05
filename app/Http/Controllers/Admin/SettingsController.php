<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct()
    {
        // Middleware tanımını kaldırıp method'da kontrol yapalım
    }

    /**
     * Ayarları göster
     */
    public function index()
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $settings = Settings::orderBy('group')->orderBy('label')->get();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Ayarları güncelle
     */
    public function update(Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:1000',
            'settings.founding_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico,jpeg,jpg,gif|max:1024',
        ]);

        // Logo yükleme işlemi
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');

            // Dosya türünü kontrol et
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!in_array($logo->getMimeType(), $allowedTypes)) {
                return redirect()->back()
                    ->withErrors(['logo' => 'Sadece JPEG, PNG ve GIF formatları desteklenir.'])
                    ->withInput();
            }

            // Dosya boyutunu kontrol et (2MB)
            if ($logo->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()
                    ->withErrors(['logo' => 'Logo dosyası 2MB\'dan büyük olamaz.'])
                    ->withInput();
            }

            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('logos', $logoName, 'public');

            // Eski logoyu sil
            $oldLogo = Settings::get('logo');
            if ($oldLogo && \Storage::disk('public')->exists($oldLogo)) {
                \Storage::disk('public')->delete($oldLogo);
            }

            // Yeni logo yolunu kaydet
            Settings::set('logo', $logoPath);
        }

        // Favicon yükleme işlemi
        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');

            // Dosya türünü kontrol et
            $allowedTypes = ['image/png', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/jpeg', 'image/jpg', 'image/gif'];
            if (!in_array($favicon->getMimeType(), $allowedTypes)) {
                return redirect()->back()
                    ->withErrors(['favicon' => 'Sadece PNG, ICO, JPEG ve GIF formatları desteklenir.'])
                    ->withInput();
            }

            // Dosya boyutunu kontrol et (1MB)
            if ($favicon->getSize() > 1024 * 1024) {
                return redirect()->back()
                    ->withErrors(['favicon' => 'Favicon dosyası 1MB\'dan büyük olamaz.'])
                    ->withInput();
            }

            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $faviconPath = $favicon->storeAs('favicons', $faviconName, 'public');

            // Eski favicon'u sil
            $oldFavicon = Settings::get('favicon');
            if ($oldFavicon && \Storage::disk('public')->exists($oldFavicon)) {
                \Storage::disk('public')->delete($oldFavicon);
                // Public storage'dan da sil
                $publicFaviconPath = public_path('storage/' . $oldFavicon);
                if (file_exists($publicFaviconPath)) {
                    unlink($publicFaviconPath);
                }
            }

            // Yeni favicon yolunu kaydet
            Settings::set('favicon', $faviconPath);

            // Public storage'a da kopyala (Windows symlink sorunları için)
            $sourcePath = storage_path('app/public/' . $faviconPath);
            $publicPath = public_path('storage/' . $faviconPath);
            $publicDir = dirname($publicPath);
            
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $publicPath);
            }
        }

        // Adres alanlarını birleştir
        if (isset($request->settings['organization_street']) ||
            isset($request->settings['organization_house_number']) ||
            isset($request->settings['organization_postal_code']) ||
            isset($request->settings['organization_city'])) {

            $street = $request->settings['organization_street'] ?? '';
            $houseNumber = $request->settings['organization_house_number'] ?? '';
            $postalCode = $request->settings['organization_postal_code'] ?? '';
            $city = $request->settings['organization_city'] ?? '';

            // Adres formatı: Strasse Haus Nummer, PLZ Stadt
            $addressParts = [];
            if ($street && $houseNumber) {
                $addressParts[] = $street . ' ' . $houseNumber;
            } elseif ($street) {
                $addressParts[] = $street;
            }

            if ($postalCode && $city) {
                $addressParts[] = $postalCode . ' ' . $city;
            } elseif ($city) {
                $addressParts[] = $city;
            }

            $fullAddress = implode(', ', $addressParts);
            Settings::set('organization_address', $fullAddress);
        }

        // Diğer ayarları güncelle
        foreach ($request->settings as $key => $value) {
            Settings::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'İşleminiz gerçekleştirildi! Ayarlar başarıyla güncellendi.');
    }
}
