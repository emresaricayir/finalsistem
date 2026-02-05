<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\User;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::where('email', 'admin@example.com')->first();

        if (!$adminUser) {
            $adminUser = User::first();
        }

        $createdBy = $adminUser ? $adminUser->id : 1;

        // Ana menü öğeleri
        $mainMenus = [
            [
                'title' => 'ANASAYFA',
                'route_name' => null,
                'url' => '/',
                'sort_order' => 1
            ],
            [
                'title' => 'KURUMSAL YAPI',
                'route_name' => 'corporate',
                'sort_order' => 2,
                'has_dropdown' => true
            ],
            [
                'title' => 'GÜNCEL',
                'route_name' => null,
                'url' => '#',
                'sort_order' => 3,
                'has_dropdown' => true
            ],
            [
                'title' => 'KURSLAR',
                'route_name' => 'courses',
                'sort_order' => 4,
                'has_dropdown' => true
            ],
            [
                'title' => 'HİZMETLER',
                'route_name' => 'services',
                'sort_order' => 5,
                'has_dropdown' => true
            ],
            [
                'title' => 'ETKİNLİKLER',
                'route_name' => 'events',
                'sort_order' => 6
            ],
            [
                'title' => 'GALERİ',
                'route_name' => 'gallery',
                'sort_order' => 7,
                'has_dropdown' => true
            ],
            [
                'title' => 'İLETİŞİM',
                'route_name' => 'contact',
                'sort_order' => 8
            ]
        ];

        foreach ($mainMenus as $menuData) {
            $menu = Menu::create(array_merge($menuData, [
                'is_active' => true,
                'created_by' => $createdBy
            ]));

            // Dropdown menüler için alt menüler ekle
            if ($menu->has_dropdown) {
                $this->addSubMenus($menu, $createdBy);
            }
        }
    }

    private function addSubMenus(Menu $parentMenu, $createdBy)
    {
        $subMenus = [];

        switch ($parentMenu->title) {
            case 'KURUMSAL YAPI':
                $subMenus = [
                    ['title' => 'Hakkımızda', 'route_name' => 'about'],
                    ['title' => 'Yönetim Kurulu', 'route_name' => 'board'],
                    ['title' => 'Tarihçe', 'route_name' => 'history']
                ];
                break;

            case 'GÜNCEL':
                $subMenus = [
                    ['title' => 'Haberler', 'route_name' => 'news.all'],
                    ['title' => 'Duyurular', 'route_name' => 'announcements.all'],
                    ['title' => 'Etkinlikler', 'route_name' => null, 'url' => '#']
                ];
                break;

            case 'KURSLAR':
                $subMenus = [
                    ['title' => 'Kuran Kursu', 'route_name' => 'courses.quran'],
                    ['title' => 'Arapça Kursu', 'route_name' => 'courses.arabic'],
                    ['title' => 'Türkçe Kursu', 'route_name' => 'courses.turkish']
                ];
                break;

            case 'HİZMETLER':
                $subMenus = [
                    ['title' => 'Üyelik İşlemleri', 'route_name' => 'services.membership'],
                    ['title' => 'Sosyal Hizmetler', 'route_name' => 'services.social'],
                    ['title' => 'Danışmanlık', 'route_name' => 'services.consulting']
                ];
                break;

            case 'GALERİ':
                $subMenus = [
                    ['title' => 'Fotoğraflar', 'route_name' => 'gallery.photos'],
                    ['title' => 'Videolar', 'route_name' => 'gallery.videos'],
                    ['title' => 'Etkinlik Görselleri', 'route_name' => 'gallery.events']
                ];
                break;
        }

        foreach ($subMenus as $index => $subMenuData) {
            Menu::create(array_merge($subMenuData, [
                'parent_id' => $parentMenu->id,
                'sort_order' => $index + 1,
                'is_active' => true,
                'created_by' => $createdBy
            ]));
        }
    }
}
