<?php

namespace App\Console\Commands;

use App\Models\Due;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MarkAllOverduePaid extends Command
{
    protected $signature = 'dues:mark-all-overdue-paid {--force : Onay istemeden çalıştır}';
    protected $description = 'Tüm üyelerin gecikmiş aidatlarını ödendi olarak işaretle';

    public function handle()
    {
        $this->info('🔍 Gecikmiş aidatlar kontrol ediliyor...');
        $this->newLine();

        // Gecikmiş aidatları al
        $overdueDues = Due::where('status', 'overdue')
            ->with('member')
            ->orderBy('due_date')
            ->get();

        if ($overdueDues->isEmpty()) {
            $this->info('✅ Gecikmiş aidat bulunamadı!');
            return Command::SUCCESS;
        }

        $this->line("📊 Toplam gecikmiş aidat sayısı: <fg=red;options=bold>{$overdueDues->count()}</>");
        $this->line("💰 Toplam tutar: <fg=red;options=bold>" . number_format($overdueDues->sum('amount'), 2) . " €</>");
        $this->newLine();

        // Üye bazında özet
        $memberSummary = $overdueDues->groupBy('member_id');
        $this->line("👥 Gecikmiş aidatı olan üye sayısı: <fg=yellow;options=bold>{$memberSummary->count()}</>");
        $this->newLine();

        // Onay iste
        if (!$this->option('force')) {
            if (!$this->confirm('Tüm gecikmiş aidatları ödendi yapmak istediğinizden emin misiniz?')) {
                $this->warn('❌ İşlem iptal edildi.');
                return Command::FAILURE;
            }
        }

        $this->newLine();
        $this->info('⏳ Aidatlar işleniyor...');
        $this->newLine();

        $processedCount = 0;
        $totalAmount = 0;
        $errors = [];

        $progressBar = $this->output->createProgressBar($overdueDues->count());
        $progressBar->start();

        DB::beginTransaction();

        try {
            foreach ($overdueDues as $due) {
                try {
                    $member = $due->member;

                    // Üyenin ödeme yöntemini kullan, yoksa varsayılan olarak bank_transfer
                    $paymentMethod = $member->payment_method ?? 'bank_transfer';

                    // Ay ismi al
                    $monthNames = [
                        1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                        5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                        9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                    ];
                    $monthName = $monthNames[$due->month] ?? 'Bilinmeyen';

                    // Payment oluştur
                    $payment = Payment::create([
                        'member_id' => $member->id,
                        'amount' => $due->amount,
                        'payment_method' => $paymentMethod,
                        'payment_date' => now(),
                        'description' => "Toplu ödeme - {$monthName} {$due->year}",
                        'recorded_by' => auth()->id() ?? 1, // Varsayılan admin kullanıcı
                    ]);

                    // Due'yu payment ile pivot tablo üzerinden ilişkilendir
                    $payment->dues()->attach($due->id, ['amount' => $due->amount]);

                    // Due durumunu güncelle (Observer otomatik yapacak ama yine de güvenlik için)
                    $due->refresh();
                    if ($due->status !== 'paid') {
                        $due->status = 'paid';
                        $due->paid_date = now();
                        $due->save();
                    }

                    $processedCount++;
                    $totalAmount += $due->amount;

                } catch (\Exception $e) {
                    $errors[] = [
                        'member' => $member->full_name ?? 'Bilinmeyen',
                        'due_id' => $due->id,
                        'error' => $e->getMessage()
                    ];
                }

                $progressBar->advance();
            }

            DB::commit();
            $progressBar->finish();

            $this->newLine(2);
            $this->info('✅ İşlem tamamlandı!');
            $this->newLine();

            // Sonuç özeti
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->line("📊 <fg=green;options=bold>İŞLEM ÖZETİ</>");
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->line("✓ İşlenen aidat sayısı: <fg=green;options=bold>{$processedCount}</>");
            $this->line("💰 Toplam tutar: <fg=green;options=bold>" . number_format($totalAmount, 2) . " €</>");

            if (!empty($errors)) {
                $this->newLine();
                $this->warn("⚠️  {count($errors)} adet hata oluştu:");
                foreach ($errors as $error) {
                    $this->error("  - {$error['member']} (Due ID: {$error['due_id']}): {$error['error']}");
                }
            }

            $this->newLine();
            $this->info('🎉 Tüm gecikmiş aidatlar başarıyla ödendi olarak işaretlendi!');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error('❌ Kritik hata oluştu, tüm işlemler geri alındı!');
            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}

