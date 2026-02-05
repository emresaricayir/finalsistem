<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\Cache;

class MenuService
{
    public function getMainMenu()
    {
        return Cache::remember('main_menu', 3600, function () {
            return Menu::with('children')
                ->parents()
                ->active()
                ->ordered()
                ->get();
        });
    }

    public function getMenuBySlug($slug)
    {
        return Menu::where('route_name', $slug)
            ->orWhere('url', $slug)
            ->active()
            ->first();
    }

    public function clearCache()
    {
        Cache::forget('main_menu');
    }

    public function buildMenuUrl(Menu $menu)
    {
        if ($menu->url) {
            return $menu->url;
        }

        if ($menu->route_name) {
            try {
                \Log::info('MenuService: Building URL for menu', [
                    'id' => $menu->id,
                    'title' => $menu->title,
                    'route_name' => $menu->route_name,
                    'slug' => $menu->slug
                ]);

                if ($menu->route_name === 'page.show') {
                    if ($menu->slug) {
                        try {
                            return route($menu->route_name, ['slug' => $menu->slug]);
                        } catch (\Exception $e) {
                            \Log::error('MenuService: Error generating page.show route', [
                                'menu_id' => $menu->id,
                                'slug' => $menu->slug,
                                'error' => $e->getMessage()
                            ]);
                            return '#';
                        }
                    } else {
                        // Slug eksik, güvenli fallback
                        \Log::warning('MenuService: page.show route without slug', ['menu_id' => $menu->id]);
                        return '#';
                    }
                }

                // Sabit sayfalar için route kontrolü
                if (in_array($menu->route_name, ['welcome', 'news.all', 'announcements.all', 'member.application', 'member.login', 'din-gorevlileri.index'])) {
                    try {
                        return route($menu->route_name);
                    } catch (\Exception $e) {
                        \Log::error('MenuService: Error generating route', [
                            'menu_id' => $menu->id,
                            'route' => $menu->route_name,
                            'error' => $e->getMessage()
                        ]);
                        return '#';
                    }
                }

                if ($menu->route_name === 'board-members.index') {
                    try {
                        return route($menu->route_name);
                    } catch (\Exception $e) {
                        \Log::error('MenuService: Error generating board-members.index route', [
                            'menu_id' => $menu->id,
                            'error' => $e->getMessage()
                        ]);
                        return '#';
                    }
                }

                if ($menu->route_name === 'personnel-category' && $menu->category_id) {
                    try {
                        return route($menu->route_name, ['category' => $menu->category_id]);
                    } catch (\Exception $e) {
                        \Log::error('MenuService: Error generating personnel-category route', [
                            'menu_id' => $menu->id,
                            'category_id' => $menu->category_id,
                            'error' => $e->getMessage()
                        ]);
                        return '#';
                    }
                }

                // Check if route exists
                if (\Route::has($menu->route_name)) {
                    return route($menu->route_name);
                }

                \Log::warning('MenuService: Route not found', ['route' => $menu->route_name]);
                return '#';

            } catch (\Exception $e) {
                \Log::error('MenuService: Error building URL', [
                    'menu_id' => $menu->id,
                    'error' => $e->getMessage()
                ]);
                // Route bulunamadı veya parametre eksik
                return '#';
            }
        }

        return '#';
    }
}
