<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TestObituarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin kullanıcısını bul veya oluştur
        $admin = User::firstOrCreate(
            ['email' => 'admin@ayasofya.de'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Test vefat duyurusu oluştur
        Announcement::create([
            'title' => 'Test Vefat Duyurusu',
            'content' => 'Bu bir test vefat duyurusudur.',
            'type' => 'obituary',
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 0,
            'created_by' => $admin->id,
            'obituary_name' => 'Ahmet Yılmaz',
            'obituary_date' => '2025-01-15',
            'funeral_time' => '14:30',
            'funeral_place' => 'Ayasofya Camii',
            'burial_place' => 'Kornwestheim Mezarlığı',
        ]);

        $this->command->info('Test vefat duyurusu oluşturuldu!');
    }
}
