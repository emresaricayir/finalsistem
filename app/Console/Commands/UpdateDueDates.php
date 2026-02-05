<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Due;
use Carbon\Carbon;

class UpdateDueDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:update-dates {--dry-run : Sadece önizleme}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mevcut aidatların son ödeme tarihlerini ayın sonuna günceller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📅 Aidat tarihlerini güncelleme işlemi başlatılıyor...');

        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN - Gerçek işlem yapılmayacak, sadece önizleme');
        }

        $dues = Due::all();
        $this->info("📊 Toplam {$dues->count()} aidat bulundu.");

        $updatedCount = 0;

        foreach ($dues as $due) {
            // Yeni son ödeme tarihi: ayın son günü
            $newDueDate = Carbon::createFromDate($due->year, $due->month, 1)->endOfMonth();

            // Eğer tarih farklıysa güncelle
            if ($due->due_date->format('Y-m-d') !== $newDueDate->format('Y-m-d')) {
                $this->line("📝 {$due->member->name} {$due->member->surname} - {$due->year}/{$due->month}: {$due->due_date->format('d.m.Y')} → {$newDueDate->format('d.m.Y')}");

                if (!$isDryRun) {
                    $due->update(['due_date' => $newDueDate]);
                }

                $updatedCount++;
            }
        }

        $this->newLine();
        $this->info("✅ Güncellenen aidat sayısı: {$updatedCount}");

        if ($isDryRun) {
            $this->warn('🔍 Bu bir önizlemeydi. Gerçek işlem için --dry-run parametresini kaldırın.');
        } else {
            $this->info('🎉 Aidat tarihleri başarıyla güncellendi!');
        }
    }
}
