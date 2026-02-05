<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuickAccess;
use App\Models\User;

class QuickAccessSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::first() ?? User::factory()->create();

        $quickAccessItems = [
            [
                'title' => 'HİZMETLER',
                'description' => 'Kocaeli Büyükşehir Belediyesi hizmetlerini keşfedin',
                'icon' => 'fa-cogs',
                'url' => 'https://www.kocaeli.bel.tr/hizmetler',
                'sort_order' => 1,
                'is_active' => true,
                'created_by' => $adminUser->id
            ],
            [
                'title' => '41 Kart Bebek Destek Programı',
                'description' => 'Ekonomik durumu yeterli olmayan, 0-3 yaş arasında iki çocuğu olan ailelere destek',
                'icon' => 'fa-hands-helping',
                'url' => 'https://www.kocaeli.bel.tr/bebek-destek',
                'sort_order' => 2,
                'is_active' => true,
                'created_by' => $adminUser->id
            ],
            [
                'title' => '41 Kart Gıda Destek Programı',
                'description' => 'Kocaeli Büyükşehir Belediyesi Gıda Destek Programını keşfedin',
                'icon' => 'fa-box',
                'url' => 'https://www.kocaeli.bel.tr/gida-destek',
                'sort_order' => 3,
                'is_active' => true,
                'created_by' => $adminUser->id
            ],
            [
                'title' => '41 Kart Giyim Destek Programı',
                'description' => 'Kocaeli Büyükşehir Belediyesi Giyim Destek Programını keşfedin',
                'icon' => 'fa-users',
                'url' => 'https://www.kocaeli.bel.tr/giyim-destek',
                'sort_order' => 4,
                'is_active' => true,
                'created_by' => $adminUser->id
            ],
            [
                'title' => '41 Kart Kırtasiye Destek Programı',
                'description' => 'Ekonomik durumu yeterli olmayan, 41 Kart destek programından yararlanan ailelere destek',
                'icon' => 'fa-hands-helping',
                'url' => 'https://www.kocaeli.bel.tr/kirtasiye-destek',
                'sort_order' => 5,
                'is_active' => true,
                'created_by' => $adminUser->id
            ],
            [
                'title' => '41 Kart Medikal Destek Programı',
                'description' => 'Ekonomik durumu yeterli olmayan bireylere her ay medikal destek sağlanmaktadır',
                'icon' => 'fa-heartbeat',
                'url' => 'https://www.kocaeli.bel.tr/medikal-destek',
                'sort_order' => 6,
                'is_active' => true,
                'created_by' => $adminUser->id
            ]
        ];

        foreach ($quickAccessItems as $item) {
            QuickAccess::create($item);
        }
    }
}
