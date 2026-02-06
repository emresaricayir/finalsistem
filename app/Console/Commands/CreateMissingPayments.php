<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Due;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class CreateMissingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:missing-payments {--dry-run : Show what would be created without actually creating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create missing payment records for dues with status=paid but no payment records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - Hiçbir değişiklik yapılmayacak');
        } else {
            $this->warn('⚠️  GERÇEK MOD - Payment kayıtları oluşturulacak!');
            if (!$this->confirm('Devam etmek istediğinizden emin misiniz?')) {
                $this->info('İşlem iptal edildi.');
                return;
            }
        }

        $this->info('Problematik aidatlar aranıyor...');

        // Status = 'paid' ama ödeme kaydı olmayan aidatlar
        $problematicDues = Due::where('status', 'paid')
            ->whereDoesntHave('payments')
            ->whereDoesntHave('paymentDues')
            ->with('member')
            ->get();

        $this->info('Toplam problematik aidat: ' . $problematicDues->count());

        if ($problematicDues->count() === 0) {
            $this->info('✅ Problematik aidat bulunamadı!');
            return;
        }

        $createdCount = 0;
        $bar = $this->output->createProgressBar($problematicDues->count());
        $bar->start();

        // Admin kullanıcısını bul (sistem işlemi için)
        $adminUser = User::where('is_admin', true)->first();
        if (!$adminUser) {
            $this->error('❌ Admin kullanıcı bulunamadı!');
            return Command::FAILURE;
        }

        foreach ($problematicDues as $due) {
            // ÖNEMLİ: Duplicate kontrolü - Bu aidat için zaten ödeme kaydı var mı?
            // (whereDoesntHave kontrolü var ama yine de güvenlik için kontrol edelim)
            if (Payment::isDueAlreadyPaid($due->id)) {
                $this->warn("⚠️  Aidat ID {$due->id} için zaten ödeme kaydı var, atlanıyor...");
                continue;
            }

            if (!$dryRun) {
                // Payment kaydı oluştur
                $payment = Payment::create([
                    'member_id' => $due->member_id,
                    'due_id' => $due->id, // Eski sistem için
                    'amount' => $due->amount,
                    'payment_method' => 'bank_transfer', // Default olarak banka havalesi
                    'payment_date' => Carbon::create($due->year, $due->month, 15), // Ayın 15'i
                    'receipt_no' => 'AUTO-' . $due->year . '-' . str_pad($due->month, 2, '0', STR_PAD_LEFT) . '-' . $due->member_id,
                    'description' => 'Otomatik oluşturuldu - Eksik ödeme kaydı düzeltmesi',
                    'recorded_by' => $adminUser->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Yeni sistem için pivot table'a da ekle
                $payment->dues()->attach($due->id, [
                    'amount' => $due->amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $createdCount++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        if ($dryRun) {
            $this->info("📋 DRY RUN: {$createdCount} adet Payment kaydı oluşturulacaktı");
        } else {
            $this->info("✅ {$createdCount} adet Payment kaydı başarıyla oluşturuldu!");
        }

        // İstatistik göster
        $this->newLine();
        $this->info('=== ÖZET ===');

        $memberStats = [];
        foreach ($problematicDues as $due) {
            $memberKey = $due->member->name . ' ' . $due->member->surname . ' (' . $due->member->member_no . ')';
            if (!isset($memberStats[$memberKey])) {
                $memberStats[$memberKey] = 0;
            }
            $memberStats[$memberKey]++;
        }

        $this->info('Etkilenen üye sayısı: ' . count($memberStats));

        // İlk 10 üyeyi göster
        $this->info('=== İLK 10 ÜYE ===');
        $count = 0;
        foreach ($memberStats as $member => $dueCount) {
            $this->line($member . ': ' . $dueCount . ' aidat');
            $count++;
            if ($count >= 10) break;
        }

        if (!$dryRun) {
            $this->newLine();
            $this->info('🎉 İşlem tamamlandı! Artık raporda bu aidatlar görünecek.');
        }

        return Command::SUCCESS;
    }
}
