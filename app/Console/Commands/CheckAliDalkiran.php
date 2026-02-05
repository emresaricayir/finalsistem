<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Due;
use App\Models\Payment;

class CheckAliDalkiran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:ali-dalkiran';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Ali Dalkiran dues and payments status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Ali Dalkıran kontrol ediliyor...');

        // Ali Dalkıran'ı bul
        $ali = Member::where('name', 'LIKE', '%ali%')
                    ->where('surname', 'LIKE', '%dalkıran%')
                    ->first();

        if (!$ali) {
            $this->error('❌ Ali Dalkıran bulunamadı!');
            return Command::FAILURE;
        }

        $this->info("✅ Ali Dalkıran bulundu: {$ali->name} {$ali->surname} (ID: {$ali->id}, Üye No: {$ali->member_no})");
        $this->newLine();

        // 2024 aidatlarını kontrol et
        $this->info('=== 2024 AİDATLARI ===');
        $dues2024 = Due::where('member_id', $ali->id)
                       ->where('year', 2024)
                       ->orderBy('month')
                       ->get();

        $this->info("Toplam 2024 aidat sayısı: {$dues2024->count()}");
        $this->newLine();

        foreach ($dues2024 as $due) {
            // Eski sistem payment kontrolü
            $oldPayments = Payment::where('due_id', $due->id)->count();

            // Yeni sistem payment kontrolü
            $newPayments = $due->paymentDues()->count();

            $totalPayments = $oldPayments + $newPayments;

            $status = $totalPayments > 0 ? '✅ ÖDENDİ' : '❌ ÖDENMEDİ';
            $paymentInfo = "Eski: {$oldPayments}, Yeni: {$newPayments}, Toplam: {$totalPayments}";

            $this->line("2024-{$due->month}: Status={$due->status}, {$status} ({$paymentInfo})");
        }

        $this->newLine();

        // Toplam istatistik
        $paidDues = $dues2024->filter(function($due) {
            $oldPayments = Payment::where('due_id', $due->id)->count();
            $newPayments = $due->paymentDues()->count();
            return $oldPayments > 0 || $newPayments > 0;
        });

        $this->info("=== ÖZET ===");
        $this->info("Toplam 2024 aidat: {$dues2024->count()}");
        $this->info("Ödeme kaydı olan: {$paidDues->count()}");
        $this->info("Ödeme kaydı olmayan: " . ($dues2024->count() - $paidDues->count()));

        if ($paidDues->count() === $dues2024->count()) {
            $this->info("🎉 Ali Dalkıran'ın tüm 2024 aidatları ödeme kaydına sahip!");
            $this->info("📊 Raporda tüm aylar X ile işaretlenecek.");
        } else {
            $this->warn("⚠️  Bazı aidatlar hala ödeme kaydı olmadan!");
        }

        return Command::SUCCESS;
    }
}
