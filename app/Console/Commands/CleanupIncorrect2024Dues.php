<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Due;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupIncorrect2024Dues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:cleanup-2024 {--dry-run : Sadece analiz yap, değişiklik yapma}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '01.01.2025\'ten önce üye olanların 2024 aidatlarını temizle (yeni mantığa göre 01.01.2025\'ten başlamalı)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        $this->info('2024 Aidatları Temizleme İşlemi Başlatılıyor...');
        $this->newLine();

        // Referans tarihten önce üye olanları bul
        $cutoffDate = \App\Services\DuesValidationService::getReferenceDate();
        $oldMembers = Member::where('membership_date', '<', $cutoffDate)->get();

        $this->info("01.01.2025'ten önce üye olan üye sayısı: " . $oldMembers->count());

        $totalDuesToRemove = 0;
        $membersWithIncorrectDues = 0;

        foreach ($oldMembers as $member) {
            // Bu üyenin 2024 aidatlarını kontrol et
            $dues2024 = $member->dues()->where('year', 2024)->get();

            if ($dues2024->count() > 0) {
                $membersWithIncorrectDues++;
                $totalDuesToRemove += $dues2024->count();

                $this->line("• {$member->name} {$member->surname} (Üyelik: {$member->membership_date->format('d.m.Y')}) - 2024'te {$dues2024->count()} aidat");

                if (!$isDryRun) {
                    // Sadece ödenmemiş aidatları sil
                    $unpaidDues = $dues2024->where('status', '!=', 'paid');
                    if ($unpaidDues->count() > 0) {
                        $unpaidDues->each(function($due) {
                            $due->delete();
                        });
                        $this->info("  ✓ {$unpaidDues->count()} ödenmemiş aidat silindi");
                    }

                    // Ödenmiş aidatları uyar
                    $paidDues = $dues2024->where('status', 'paid');
                    if ($paidDues->count() > 0) {
                        $this->warn("  ⚠ {$paidDues->count()} ödenmiş aidat korundu (silinmedi)");
                    }
                }
            }
        }

        $this->newLine();
        $this->info("ÖZET:");
        $this->info("• Yanlış 2024 aidatı olan üye sayısı: {$membersWithIncorrectDues}");
        $this->info("• Toplam yanlış 2024 aidat sayısı: {$totalDuesToRemove}");

        if ($isDryRun) {
            $this->newLine();
            $this->warn("DRY RUN MODU - Hiçbir değişiklik yapılmadı!");
            $this->info("Gerçek temizleme için --dry-run parametresini kaldırın.");
        } else {
            $this->newLine();
            $this->info("Temizleme işlemi tamamlandı!");
            $this->info("Sadece ödenmemiş aidatlar silindi, ödenmiş aidatlar korundu.");
        }

        return 0;
    }
}
