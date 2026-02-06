<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class CleanupDuplicatePayments extends Command
{
    protected $signature = 'payments:cleanup-duplicates 
                            {--dry-run : Sadece duplicate\'leri göster, silme}
                            {--member-id= : Belirli bir üye için temizle}';

    protected $description = 'Production\'da güvenli bir şekilde duplicate ödemeleri temizler';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $memberId = $this->option('member-id');

        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODU: Hiçbir ödeme silinmeyecek, sadece gösterilecek');
            $this->newLine();
        }

        $this->info('🔍 Duplicate ödemeler aranıyor...');
        $this->newLine();

        // 1. Aynı member, amount, date ve aynı aidatlara bağlı duplicate'leri bul
        $query = "
            SELECT 
                p1.id as payment1_id,
                p2.id as payment2_id,
                p1.member_id,
                m.name,
                m.surname,
                p1.amount,
                p1.payment_date,
                p1.created_at as payment1_created,
                p2.created_at as payment2_created,
                GROUP_CONCAT(DISTINCT pd1.due_id ORDER BY pd1.due_id) as due_ids1,
                GROUP_CONCAT(DISTINCT pd2.due_id ORDER BY pd2.due_id) as due_ids2
            FROM payments p1
            INNER JOIN payments p2 ON p1.member_id = p2.member_id 
                AND p1.amount = p2.amount 
                AND DATE(p1.payment_date) = DATE(p2.payment_date)
                AND p1.id < p2.id
            LEFT JOIN payment_due pd1 ON p1.id = pd1.payment_id
            LEFT JOIN payment_due pd2 ON p2.id = pd2.payment_id
            INNER JOIN members m ON p1.member_id = m.id
            WHERE p1.deleted_at IS NULL 
              AND p2.deleted_at IS NULL
        ";

        if ($memberId) {
            $query .= " AND p1.member_id = " . (int)$memberId;
        }

        $query .= "
            GROUP BY p1.id, p2.id, p1.member_id, p1.amount, p1.payment_date, p1.created_at, p2.created_at, m.name, m.surname
            HAVING GROUP_CONCAT(DISTINCT pd1.due_id ORDER BY pd1.due_id) = GROUP_CONCAT(DISTINCT pd2.due_id ORDER BY pd2.due_id)
               OR (GROUP_CONCAT(DISTINCT pd1.due_id) IS NULL AND GROUP_CONCAT(DISTINCT pd2.due_id) IS NULL)
            ORDER BY p1.member_id, p1.payment_date, p1.created_at
        ";

        $duplicates = DB::select($query);

        if (empty($duplicates)) {
            $this->info('✅ Duplicate ödeme bulunamadı!');
            return Command::SUCCESS;
        }

        $this->warn("⚠️  " . count($duplicates) . " adet duplicate ödeme bulundu!");
        $this->newLine();

        // Tablo başlıkları
        $headers = ['Üye', 'Tutar', 'Tarih', 'Eski Ödeme ID', 'Yeni Ödeme ID', 'Durum'];
        $rows = [];

        $totalAmount = 0;
        $deletedCount = 0;

        foreach ($duplicates as $dup) {
            $memberName = $dup->name . ' ' . $dup->surname;
            $amount = number_format($dup->amount, 2) . ' €';
            $date = date('d.m.Y', strtotime($dup->payment_date));
            
            $totalAmount += $dup->amount;

            if ($dryRun) {
                $status = '🔍 Bulundu (silinecek)';
                $rows[] = [$memberName, $amount, $date, $dup->payment1_id, $dup->payment2_id, $status];
            } else {
                // En eski ödemeyi koru (payment1_id < payment2_id olduğu için payment1_id daha eski)
                // payment2_id'yi soft delete yap
                DB::table('payments')
                    ->where('id', $dup->payment2_id)
                    ->update(['deleted_at' => now()]);
                
                $deletedCount++;
                $status = '✅ Silindi';
                $rows[] = [$memberName, $amount, $date, $dup->payment1_id, $dup->payment2_id, $status];
            }
        }

        $this->table($headers, $rows);
        $this->newLine();

        if ($dryRun) {
            $this->info("📊 Özet:");
            $this->line("   - Bulunan duplicate sayısı: " . count($duplicates));
            $this->line("   - Toplam tutar (duplicate'ler): " . number_format($totalAmount, 2) . " €");
            $this->line("   - Temizlendikten sonra düşecek tutar: " . number_format($totalAmount, 2) . " €");
            $this->newLine();
            $this->warn("⚠️  Bu duplicate'leri temizlemek için --dry-run parametresini kaldırın:");
            $this->line("   php artisan payments:cleanup-duplicates");
            if ($memberId) {
                $this->line("   php artisan payments:cleanup-duplicates --member-id={$memberId}");
            }
        } else {
            $this->info("✅ İşlem tamamlandı!");
            $this->line("   - Temizlenen duplicate sayısı: {$deletedCount}");
            $this->line("   - Düşen toplam tutar: " . number_format($totalAmount, 2) . " €");
            $this->newLine();
            $this->info("💡 Not: Ödemeler soft delete yapıldı (deleted_at set edildi).");
            $this->info("   Geri almak isterseniz: UPDATE payments SET deleted_at = NULL WHERE id IN (...);");
        }

        return Command::SUCCESS;
    }
}
