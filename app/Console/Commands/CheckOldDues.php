<?php

namespace App\Console\Commands;

use App\Models\Due;
use Illuminate\Console\Command;

class CheckOldDues extends Command
{
    protected $signature = 'dues:check-old';
    protected $description = 'Check for dues before 2025';

    public function handle()
    {
        $this->info('🔍 2024 ve öncesi aidatlar kontrol ediliyor...');

        $oldDues = Due::where('year', '<', 2025)
            ->with('member:id,name,surname')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        if ($oldDues->isEmpty()) {
            $this->info('✅ Sistemde 2024 ve öncesi aidat yok!');
            return Command::SUCCESS;
        }

        $this->error("❌ Toplam {$oldDues->count()} adet 2024 ve öncesi aidat bulundu:");
        $this->newLine();

        $table = [];
        foreach ($oldDues as $due) {
            $table[] = [
                'Üye' => $due->member->name . ' ' . $due->member->surname,
                'Tarih' => $due->month . '/' . $due->year,
                'Tutar' => number_format($due->amount, 2) . ' €',
                'Durum' => $due->status,
            ];
        }

        $this->table(['Üye', 'Tarih', 'Tutar', 'Durum'], $table);

        $this->newLine();
        $this->warn('Bu aidatları silmek ister misiniz? Komutu çalıştırın:');
        $this->line('php artisan dues:delete-old');

        return Command::SUCCESS;
    }
}



