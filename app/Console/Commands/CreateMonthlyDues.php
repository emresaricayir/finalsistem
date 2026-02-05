<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Due;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CreateMonthlyDues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:create-monthly {--month= : Belirli bir ay için (YYYY-MM formatında)} {--dry-run : Sadece önizleme, gerçek işlem yapmaz}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Her ayın 1\'inde aktif üyeler için o ayın aidatlarını oluşturur (son ödeme tarihi ayın sonu)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🗓️  Aylık aidat oluşturma işlemi başlatılıyor...');

        // Hedef ay belirleme
        $targetMonth = $this->option('month')
            ? Carbon::createFromFormat('Y-m', $this->option('month'))->startOfMonth()
            : Carbon::now()->startOfMonth();

        $isDryRun = $this->option('dry-run');

        $this->info("📅 Hedef ay: {$targetMonth->format('F Y')}");
        $this->info("📅 Son ödeme tarihi: {$targetMonth->endOfMonth()->format('d.m.Y')}");

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN - Gerçek işlem yapılmayacak, sadece önizleme');
        }

        // Aktif üyeleri al
        $activeMembers = Member::where('status', 'active')->get();
        $this->info("👥 Toplam {$activeMembers->count()} aktif üye bulundu.");

        if ($activeMembers->isEmpty()) {
            $this->warn('⚠️  Aktif üye bulunamadı!');
            return;
        }

        $createdCount = 0;
        $skippedCount = 0;
        $errors = [];

        foreach ($activeMembers as $member) {
            try {
                // Bu ay için aidat zaten var mı kontrol et
                $existingDue = Due::where('member_id', $member->id)
                    ->where('year', $targetMonth->year)
                    ->where('month', $targetMonth->month)
                    ->first();

                if ($existingDue) {
                    $this->line("⏭️  {$member->name} {$member->surname} - Zaten mevcut");
                    $skippedCount++;
                    continue;
                }

                // Üyenin kendi aidat miktarını kullan
                $memberAmount = $member->monthly_dues ?? 50.00;

                // Aidat son ödeme tarihi: ayın son günü
                $dueDate = $targetMonth->copy()->endOfMonth();

                if (!$isDryRun) {
                    Due::create([
                        'member_id' => $member->id,
                        'year' => $targetMonth->year,
                        'month' => $targetMonth->month,
                        'amount' => $memberAmount,
                        'due_date' => $dueDate,
                        'status' => 'pending',
                        'notes' => "Otomatik oluşturulan " . $targetMonth->format('F Y') . " aidatı",
                    ]);
                }

                $this->line("✅ {$member->name} {$member->surname} - €{$memberAmount} (Son tarih: {$dueDate->format('d.m.Y')})");
                $createdCount++;

            } catch (\Exception $e) {
                $error = "❌ {$member->name} {$member->surname} - Hata: " . $e->getMessage();
                $errors[] = $error;
                $this->error($error);
                Log::error("CreateMonthlyDues error for member {$member->id}: " . $e->getMessage());
            }
        }

        // Özet
        $this->newLine();
        $this->info('📊 İŞLEM ÖZETİ');
        $this->info("✅ Oluşturulan: {$createdCount}");
        $this->info("⏭️  Atlanan: {$skippedCount}");
        $this->info("❌ Hatalı: " . count($errors));

        if (!empty($errors)) {
            $this->newLine();
            $this->error('🚨 HATALAR:');
            foreach ($errors as $error) {
                $this->line($error);
            }
        }

        if ($isDryRun) {
            $this->newLine();
            $this->warn('🔍 Bu bir önizlemeydi. Gerçek işlem için --dry-run parametresini kaldırın.');
        } else {
            $this->newLine();
            $this->info('🎉 Aylık aidat oluşturma işlemi tamamlandı!');
        }
    }
}
