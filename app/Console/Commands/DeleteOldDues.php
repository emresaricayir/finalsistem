<?php

namespace App\Console\Commands;

use App\Models\Due;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldDues extends Command
{
    protected $signature = 'dues:delete-old {--force : Run without confirmation}';
    protected $description = 'Delete dues before 2025 and their payments';

    public function handle()
    {
        $this->info('🔍 2024 ve öncesi aidatlar kontrol ediliyor...');

        $oldDues = Due::where('year', '<', 2025)->with('member:id,name,surname')->get();

        if ($oldDues->isEmpty()) {
            $this->info('✅ Sistemde 2024 ve öncesi aidat yok!');
            return Command::SUCCESS;
        }

        $this->warn("❌ Toplam {$oldDues->count()} adet 2024 ve öncesi aidat bulundu:");
        $totalAmount = $oldDues->sum('amount');
        $this->line("💰 Toplam tutar: " . number_format($totalAmount, 2) . " €");

        // Grup halinde göster
        $byMember = $oldDues->groupBy('member_id');
        foreach ($byMember as $memberId => $dues) {
            $member = $dues->first()->member;
            $count = $dues->count();
            $amount = $dues->sum('amount');
            $this->line("   • {$member->name} {$member->surname}: {$count} aidat, " . number_format($amount, 2) . " €");
        }

        if (!$this->option('force') && !$this->confirm('Bu aidatları ve ilgili ödemelerini silmek istediğinizden emin misiniz?')) {
            $this->info('İşlem iptal edildi.');
            return Command::CANCEL;
        }

        $this->info("\n⏳ İşlem başlıyor...");

        DB::beginTransaction();
        try {
            $deletedPaymentsCount = 0;
            $deletedDuesCount = 0;

            foreach ($oldDues as $due) {
                // Bu aidatla ilişkili ödemeleri bul ve sil
                $payments = Payment::whereHas('dues', function($query) use ($due) {
                    $query->where('dues.id', $due->id);
                })->get();

                foreach ($payments as $payment) {
                    // Pivot ilişkisini sil
                    $payment->dues()->detach($due->id);

                    // Eğer ödemenin başka aidatı yoksa, ödemeyi de sil
                    if ($payment->dues()->count() == 0) {
                        $payment->delete();
                        $deletedPaymentsCount++;
                    }
                }

                // Aidatı sil
                $due->delete();
                $deletedDuesCount++;
            }

            DB::commit();

            $this->newLine();
            $this->info('✅ İşlem tamamlandı!');
            $this->line("   • {$deletedDuesCount} aidat silindi");
            $this->line("   • {$deletedPaymentsCount} ödeme silindi");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("\n❌ Hata oluştu: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}



