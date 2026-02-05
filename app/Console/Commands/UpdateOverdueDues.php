<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Due;

class UpdateOverdueDues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:update-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vadesi geçmiş pending aidatları overdue durumuna günceller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Gecikmiş aidatlar güncelleniyor...');

        // Vadesi geçmiş pending aidatları bul
        $overduePendingDues = Due::where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();

        if ($overduePendingDues === 0) {
            $this->info('Güncellenecek gecikmiş aidat bulunamadı.');
            return;
        }

        $this->info("Vadesi geçmiş pending aidat sayısı: {$overduePendingDues}");

        // Aidatları overdue durumuna güncelle
        $updated = Due::where('status', 'pending')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);

        $this->info("✓ {$updated} aidat 'overdue' durumuna güncellendi.");

        // Son durumu göster
        $pendingCount = Due::where('status', 'pending')->count();
        $overdueCount = Due::where('status', 'overdue')->count();
        $paidCount = Due::where('status', 'paid')->count();

        $this->info("\nGüncel durum:");
        $this->info("- Bekleyen (pending): {$pendingCount}");
        $this->info("- Gecikmiş (overdue): {$overdueCount}");
        $this->info("- Ödenmiş (paid): {$paidCount}");

        $this->info("\n✅ Gecikmiş aidatlar başarıyla güncellendi!");
    }
}
