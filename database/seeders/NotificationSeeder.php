<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notification::create([
            'title' => 'Yeni Üye Başvurusu',
            'message' => 'Emre Saricayir adlı kişi üyelik başvurusu yaptı.',
            'type' => 'info',
            'icon' => 'fa-user-plus'
        ]);

        Notification::create([
            'title' => 'Gecikmiş Aidat Uyarısı',
            'message' => '3 üyenin aidatı gecikmiş durumda.',
            'type' => 'warning',
            'icon' => 'fa-exclamation-triangle'
        ]);

        Notification::create([
            'title' => 'Sistem Güncellemesi',
            'message' => 'Sistem başarıyla güncellendi.',
            'type' => 'success',
            'icon' => 'fa-check-circle'
        ]);
    }
}
