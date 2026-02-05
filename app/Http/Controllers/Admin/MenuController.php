<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\PersonnelCategory;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index()
    {
        $menus = Menu::with('children')
            ->parents()
            ->orderBy('sort_order')
            ->get();

        $personnelCategories = PersonnelCategory::active()->ordered()->get();
        $pages = \App\Models\Page::active()->orderBy('title_tr')->get();
        $events = \App\Models\Event::active()->orderBy('title_tr')->get();

        return view('admin.menu.index', compact('menus', 'personnelCategories', 'pages', 'events'));
    }


    public function store(Request $request)
    {
        \Log::info('Menu store request:', $request->all());

        // Check if it's a dropdown menu
        $hasDropdown = $request->has('has_dropdown') && $request->has_dropdown == '1';

        $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'link_type' => $hasDropdown ? 'nullable|in:internal,external' : 'required|in:internal,external',
            'internal_link' => $hasDropdown ? 'nullable|string' : 'required_if:link_type,internal|nullable|string',
            'external_url' => $hasDropdown ? 'nullable|url|max:500' : 'required_if:link_type,external|nullable|url|max:500',
            'parent_id' => 'nullable|exists:menus,id',
            'personnel_category_id' => 'required_if:internal_link,personnel-category|nullable|exists:personnel_categories,id',
            'has_dropdown' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ], [
            'title_tr.required' => 'Menü adı (Türkçe) zorunludur.',
            'link_type.required' => 'Bağlantı türü seçilmelidir.',
            'internal_link.required_if' => 'İç sayfa seçilmelidir.',
            'external_url.required_if' => 'Dış link URL\'si girilmelidir.',
            'external_url.url' => 'Geçerli bir URL girilmelidir.',
            'parent_id.exists' => 'Seçilen üst menü bulunamadı.',
            'personnel_category_id.required_if' => 'Personel kategorisi seçilmelidir.',
            'personnel_category_id.exists' => 'Seçilen personel kategorisi bulunamadı.',
            'sort_order.integer' => 'Sıra numarası sayı olmalıdır.',
            'sort_order.min' => 'Sıra numarası 0\'dan küçük olamaz.'
        ]);

        \Log::info('Menu validation passed, processing request...');
        \Log::info('Request data after validation:', [
            'title' => $request->title,
            'link_type' => $request->link_type,
            'internal_link' => $request->internal_link,
            'personnel_category_id' => $request->personnel_category_id,
            'parent_id' => $request->parent_id,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order
        ]);

        \Log::info('Starting URL and route determination...');

        // URL veya route_name belirle
        $url = null;
        $route_name = null;
        $slug = null;

        // Dropdown menü ise link oluşturma
        if ($hasDropdown) {
            $url = '#';
            $route_name = null;
            $slug = null;
        } elseif ($request->link_type === 'internal' && $request->internal_link) {
            // Sabit sayfalar için route belirle
            switch ($request->internal_link) {
                case 'board-members.index':
                    $route_name = 'board-members.index';
                    $slug = null;
                    break;
                case 'welcome':
                    $route_name = 'welcome';
                    $slug = null;
                    break;
                case 'news.all':
                    $route_name = 'news.all';
                    $slug = null;
                    break;
                case 'announcements.all':
                    $route_name = 'announcements.all';
                    $slug = null;
                    break;
                case 'member.application':
                    $route_name = 'member.application';
                    $slug = null;
                    break;
                case 'member.login':
                    $route_name = 'member.login';
                    $slug = null;
                    break;
                case 'gallery.index':
                    $route_name = 'gallery.index';
                    $slug = null;
                    break;
                case 'video-gallery.index':
                    $route_name = 'video-gallery.index';
                    $slug = null;
                    break;
                case 'contact.index':
                    $route_name = 'contact.index';
                    $slug = null;
                    break;
                case 'din-gorevlileri.index':
                    $route_name = 'din-gorevlileri.index';
                    $slug = null;
                    break;
                case 'personnel-category':
                    $route_name = 'personnel-category';
                    $slug = null;
                    break;
                default:
                    // Check if it's an event link
                    if (str_starts_with($request->internal_link, 'event-')) {
                        $eventId = str_replace('event-', '', $request->internal_link);
                        $route_name = 'events.index';
                        $slug = null;
                        $url = route('events.index');
                    } else {
                        $route_name = 'page.show';
                        $slug = $request->internal_link;
                    }
                    break;
            }
        } elseif ($request->link_type === 'external' && $request->external_url) {
            $url = $request->external_url;
        }

        try {
            // Boolean değerleri manuel olarak dönüştür
            $isActive = $request->has('is_active') ? true : false;
            $openNewTab = $request->has('open_new_tab') ? true : false;

            $menu = Menu::create([
                'title_tr' => $request->title_tr,
                'title_de' => $request->title_de,
                'url' => $url,
                'route_name' => $route_name,
                'slug' => $slug,
                'parent_id' => $request->parent_id,
                'is_active' => $isActive,
                'has_dropdown' => $hasDropdown, // Use checkbox value
                'open_new_tab' => $openNewTab,
                'category_id' => $request->personnel_category_id,
                'sort_order' => $request->sort_order ?? 0,
                'created_by' => Auth::id()
            ]);

            \Log::info('Menu created successfully:', $menu->toArray());
        } catch (\Exception $e) {
            \Log::error('Menu creation failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['menu_creation' => 'Menü oluşturulurken bir hata oluştu: ' . $e->getMessage()]);
        }

        $this->menuService->clearCache();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menü öğesi başarıyla oluşturuldu.');
    }

    public function edit(Menu $menu)
    {
        $menus = Menu::with('children')
            ->parents()
            ->orderBy('sort_order')
            ->get();

        $personnelCategories = PersonnelCategory::active()->ordered()->get();
        $pages = \App\Models\Page::active()->orderBy('title_tr')->get();
        $events = \App\Models\Event::active()->orderBy('title_tr')->get();

        return view('admin.menu.index', compact('menus', 'menu', 'personnelCategories', 'pages', 'events'))->with('editingMenu', $menu);
    }

    public function update(Request $request, Menu $menu)
    {
        // Check if it's a dropdown menu
        $hasDropdown = $request->has('has_dropdown') && $request->has_dropdown == '1';

        $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_de' => 'nullable|string|max:255',
            'link_type' => $hasDropdown ? 'nullable|in:internal,external' : 'required|in:internal,external',
            'internal_link' => $hasDropdown ? 'nullable|string' : 'required_if:link_type,internal|nullable|string',
            'external_url' => $hasDropdown ? 'nullable|url|max:500' : 'required_if:link_type,external|nullable|url|max:500',
            'parent_id' => 'nullable|exists:menus,id',
            'personnel_category_id' => 'required_if:internal_link,personnel-category|nullable|exists:personnel_categories,id',
            'has_dropdown' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ], [
            'title_tr.required' => 'Menü adı (Türkçe) zorunludur.',
            'link_type.required' => 'Bağlantı türü seçilmelidir.',
            'internal_link.required_if' => 'İç sayfa seçilmelidir.',
            'external_url.required_if' => 'Dış link URL\'si girilmelidir.',
            'external_url.url' => 'Geçerli bir URL girilmelidir.',
            'parent_id.exists' => 'Seçilen üst menü bulunamadı.',
            'personnel_category_id.required_if' => 'Personel kategorisi seçilmelidir.',
            'personnel_category_id.exists' => 'Seçilen personel kategorisi bulunamadı.',
            'sort_order.integer' => 'Sıra numarası sayı olmalıdır.',
            'sort_order.min' => 'Sıra numarası 0\'dan küçük olamaz.'
        ]);

        // URL veya route_name belirle
        $url = null;
        $route_name = null;
        $slug = null;

        // Dropdown menü ise link oluşturma
        if ($hasDropdown) {
            $url = '#';
            $route_name = null;
            $slug = null;
        } elseif ($request->link_type === 'internal' && $request->internal_link) {
            // Sabit sayfalar için route belirle
            switch ($request->internal_link) {
                case 'board-members.index':
                    $route_name = 'board-members.index';
                    $slug = null;
                    break;
                case 'welcome':
                    $route_name = 'welcome';
                    $slug = null;
                    break;
                case 'news.all':
                    $route_name = 'news.all';
                    $slug = null;
                    break;
                case 'announcements.all':
                    $route_name = 'announcements.all';
                    $slug = null;
                    break;
                case 'member.application':
                    $route_name = 'member.application';
                    $slug = null;
                    break;
                case 'member.login':
                    $route_name = 'member.login';
                    $slug = null;
                    break;
                case 'gallery.index':
                    $route_name = 'gallery.index';
                    $slug = null;
                    break;
                case 'video-gallery.index':
                    $route_name = 'video-gallery.index';
                    $slug = null;
                    break;
                case 'contact.index':
                    $route_name = 'contact.index';
                    $slug = null;
                    break;
                case 'din-gorevlileri.index':
                    $route_name = 'din-gorevlileri.index';
                    $slug = null;
                    break;
                case 'personnel-category':
                    $route_name = 'personnel-category';
                    $slug = null;
                    break;
                default:
                    // Check if it's an event link
                    if (str_starts_with($request->internal_link, 'event-')) {
                        $eventId = str_replace('event-', '', $request->internal_link);
                        $route_name = 'events.index';
                        $slug = null;
                        $url = route('events.index');
                    } else {
                        $route_name = 'page.show';
                        $slug = $request->internal_link;
                    }
                    break;
            }
        } elseif ($request->link_type === 'external' && $request->external_url) {
            $url = $request->external_url;
        }

        // Boolean değerleri manuel olarak dönüştür
        $isActive = $request->has('is_active') ? true : false;
        $openNewTab = $request->has('open_new_tab') ? true : false;


        $menu->update([
            'title_tr' => $request->title_tr,
            'title_de' => $request->title_de,
            'url' => $url,
            'route_name' => $route_name,
            'slug' => $slug,
            'parent_id' => $request->parent_id,
            'is_active' => $isActive,
            'has_dropdown' => $hasDropdown, // Use checkbox value
            'open_new_tab' => $openNewTab,
            'category_id' => $request->personnel_category_id,
            'sort_order' => $request->sort_order ?? 0
        ]);

        $this->menuService->clearCache();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menü öğesi başarıyla güncellendi.');
    }

    public function destroy(Menu $menu)
    {
        $menu->children()->delete();
        $menu->delete();
        $this->menuService->clearCache();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menü öğesi ve alt menüleri başarıyla silindi.');
    }

    public function toggleStatus(Menu $menu)
    {
        $menu->update(['is_active' => !$menu->is_active]);
        $this->menuService->clearCache();

        $status = $menu->is_active ? 'aktif' : 'pasif';
        return redirect()->back()->with('success', "Menü öğesi {$status} yapıldı.");
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'menus' => 'required|array',
            'menus.*.id' => 'required|exists:menus,id',
            'menus.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->menus as $menuData) {
            Menu::where('id', $menuData['id'])->update(['sort_order' => $menuData['sort_order']]);
        }

        $this->menuService->clearCache();
        return response()->json(['success' => true]);
    }
}
