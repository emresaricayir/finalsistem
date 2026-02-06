<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Bu migration duplicate ödemeleri temizler:
     * - Aynı member_id, amount, payment_date ve aynı aidatlara bağlı olan ödemeler
     * - En eski ödemeyi korur, diğerlerini soft delete yapar
     */
    public function up(): void
    {
        $deletedCount = 0;
        
        // 1. Önce duplicate'leri bulalım (aynı member, amount, date ve aynı aidatlara bağlı)
        $duplicates = DB::select("
            SELECT 
                p1.id as payment1_id,
                p2.id as payment2_id,
                p1.member_id,
                p1.amount,
                p1.payment_date,
                GROUP_CONCAT(DISTINCT pd1.due_id ORDER BY pd1.due_id) as due_ids1,
                GROUP_CONCAT(DISTINCT pd2.due_id ORDER BY pd2.due_id) as due_ids2
            FROM payments p1
            INNER JOIN payments p2 ON p1.member_id = p2.member_id 
                AND p1.amount = p2.amount 
                AND DATE(p1.payment_date) = DATE(p2.payment_date)
                AND p1.id < p2.id
            LEFT JOIN payment_due pd1 ON p1.id = pd1.payment_id
            LEFT JOIN payment_due pd2 ON p2.id = pd2.payment_id
            WHERE p1.deleted_at IS NULL 
              AND p2.deleted_at IS NULL
            GROUP BY p1.id, p2.id, p1.member_id, p1.amount, p1.payment_date
            HAVING GROUP_CONCAT(DISTINCT pd1.due_id ORDER BY pd1.due_id) = GROUP_CONCAT(DISTINCT pd2.due_id ORDER BY pd2.due_id)
               OR (GROUP_CONCAT(DISTINCT pd1.due_id) IS NULL AND GROUP_CONCAT(DISTINCT pd2.due_id) IS NULL)
        ");

        foreach ($duplicates as $duplicate) {
            // En eski ödemeyi koru (payment1_id < payment2_id olduğu için payment1_id daha eski)
            // payment2_id'yi soft delete yap
            DB::table('payments')
                ->where('id', $duplicate->payment2_id)
                ->update(['deleted_at' => now()]);
            
            $deletedCount++;
        }
        
        // 2. Eğer yukarıdaki sorgu duplicate bulamazsa, sadece amount ve date'e göre kontrol et
        // (Aynı member, amount ve date'e sahip birden fazla ödeme varsa, en eski olanı koru)
        if ($deletedCount == 0) {
            $simpleDuplicates = DB::select("
                SELECT 
                    p.id,
                    p.member_id,
                    p.amount,
                    p.payment_date,
                    p.created_at,
                    (SELECT COUNT(*) 
                     FROM payments p2 
                     WHERE p2.member_id = p.member_id 
                       AND p2.amount = p.amount 
                       AND DATE(p2.payment_date) = DATE(p.payment_date)
                       AND p2.deleted_at IS NULL
                       AND p2.created_at < p.created_at) as older_count
                FROM payments p
                WHERE p.deleted_at IS NULL
                HAVING older_count > 0
                ORDER BY p.member_id, p.payment_date, p.created_at
            ");
            
            foreach ($simpleDuplicates as $duplicate) {
                // Bu ödeme için daha eski bir ödeme var, bu yüzden bu duplicate
                // Ama daha eski olanı korumak için, bu ödemeyi sil
                DB::table('payments')
                    ->where('id', $duplicate->id)
                    ->update(['deleted_at' => now()]);
                
                $deletedCount++;
            }
        }

        // 2. Eğer aidat bilgisi yoksa, sadece amount ve date'e göre duplicate kontrolü yap
        // (Bu durumda en eski ödemeyi koru, diğerlerini sil)
        $duplicatesWithoutDues = DB::select("
            SELECT 
                p1.id as payment1_id,
                p2.id as payment2_id,
                p1.member_id,
                p1.amount,
                p1.payment_date
            FROM payments p1
            INNER JOIN payments p2 ON p1.member_id = p2.member_id 
                AND p1.amount = p2.amount 
                AND p1.payment_date = p2.payment_date
                AND p1.id < p2.id
            LEFT JOIN payment_due pd1 ON p1.id = pd1.payment_id
            LEFT JOIN payment_due pd2 ON p2.id = pd2.payment_id
            WHERE p1.deleted_at IS NULL 
              AND p2.deleted_at IS NULL
              AND pd1.id IS NULL  -- Her iki ödeme de aidat bilgisine sahip değil
              AND pd2.id IS NULL
        ");

        foreach ($duplicatesWithoutDues as $duplicate) {
            // En eski ödemeyi koru (payment1_id < payment2_id olduğu için payment1_id daha eski)
            // payment2_id'yi soft delete yap
            DB::table('payments')
                ->where('id', $duplicate->payment2_id)
                ->update(['deleted_at' => now()]);
            
            $deletedCount++;
        }

        echo "Toplam {$deletedCount} duplicate ödeme temizlendi.\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bu migration geri alınamaz çünkü soft delete yapıyor
        // Eğer geri almak isterseniz, deleted_at'i NULL yapabilirsiniz
    }
};
