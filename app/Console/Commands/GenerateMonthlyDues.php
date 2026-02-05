<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Due;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateMonthlyDues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:generate-monthly {--month= : Belirli bir ay için (YYYY-MM formatında)} {--year= : Belirli bir yıl için} {--years=10 : Kaç yıllık aidat oluşturulacak}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aktif üyelere aylık aidat borcu oluşturur (varsayılan 10 yıl)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Çok yıllık aidat oluşturma işlemi başlatılıyor...');

        // Ay ve yıl belirleme
        $targetDate = $this->getTargetDate();
        $startYear = $targetDate->format('Y');
        $startMonth = $targetDate->format('m');
        $years = (int) $this->option('years');

        $this->info("📅 Başlangıç: {$targetDate->format('F Y')}");
        $this->info("📅 Süre: {$years} yıl");

        // Aktif üyeleri al
        $activeMembers = Member::where('status', 'active')->get();
        $this->info("👥 Toplam {$activeMembers->count()} aktif üye bulundu.");

        if ($activeMembers->isEmpty()) {
            $this->warn('⚠️  Aktif üye bulunamadı!');
            return;
        }

        $totalCreatedCount = 0;
        $totalSkippedCount = 0;
        $errors = [];

        foreach ($activeMembers as $member) {
            $this->line("👤 {$member->name} için aidatlar oluşturuluyor...");

            $memberCreatedCount = 0;
            $memberSkippedCount = 0;

            // Üyenin kendi aidat miktarını kullan
            $memberAmount = $member->monthly_dues ?? $this->getDefaultDueAmount();

            // Belirlenen yıl sayısı boyunca her ay için aidat oluştur
            for ($year = $startYear; $year < $startYear + $years; $year++) {
                for ($month = 1; $month <= 12; $month++) {
                    // İlk yıl için başlangıç ayından itibaren başla
                    if ($year == $startYear && $month < $startMonth) {
                        continue;
                    }

                    try {
                        // Bu ay için aidat zaten var mı kontrol et
                        $existingDue = Due::where('member_id', $member->id)
                            ->where('year', $year)
                            ->where('month', $month)
                            ->first();

                        if ($existingDue) {
                            $memberSkippedCount++;
                            continue;
                        }

                        // Aidat son ödeme tarihi: ayın son günü
                        $dueDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
                        $monthName = $dueDate->format('F Y');

                        // Yeni aidat oluştur
                        $due = Due::create([
                            'member_id' => $member->id,
                            'year' => $year,
                            'month' => $month,
                            'amount' => $memberAmount,
                            'due_date' => $dueDate,
                            'status' => 'pending',
                            'notes' => "Otomatik oluşturulan {$monthName} aidatı",
                        ]);

                        $memberCreatedCount++;

                    } catch (\Exception $e) {
                        $error = "❌ {$member->name} - {$year}-{$month} - Hata: " . $e->getMessage();
                        $this->error($error);
                        $errors[] = $error;
                        Log::error('Aidat oluşturma hatası', [
                            'member_id' => $member->id,
                            'member_name' => $member->name,
                            'month' => $month,
                            'year' => $year,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            $this->line("   ✅ {$member->name}: {$memberCreatedCount} aidat oluşturuldu, {$memberSkippedCount} atlandı");
            $totalCreatedCount += $memberCreatedCount;
            $totalSkippedCount += $memberSkippedCount;
        }

        // Sonuçları göster
        $this->newLine();
        $this->info("📊 İşlem Tamamlandı:");
        $this->info("   ✅ Toplam Oluşturulan: {$totalCreatedCount} aidat");
        $this->info("   ⏭️  Toplam Atlanan: {$totalSkippedCount} aidat (zaten mevcut)");

        if (!empty($errors)) {
            $this->error("   ❌ Hatalar: " . count($errors) . " adet");
            foreach ($errors as $error) {
                $this->error("      {$error}");
            }
        }

        $this->info("🎉 Çok yıllık aidat oluşturma işlemi tamamlandı!");
    }

    /**
     * Hedef tarihi belirle
     */
    private function getTargetDate()
    {
        if ($this->option('month')) {
            return Carbon::createFromFormat('Y-m', $this->option('month'));
        }

        if ($this->option('year')) {
            return Carbon::createFromDate($this->option('year'), now()->month, 1);
        }

        // Varsayılan olarak bir sonraki ay
        return now()->addMonth()->startOfMonth();
    }

    /**
     * Varsayılan aidat miktarını al
     */
    private function getDefaultDueAmount()
    {
        // Önce ayarlardan almayı dene
        $defaultAmount = \App\Models\Settings::get('default_due_amount');

        if ($defaultAmount && is_numeric($defaultAmount)) {
            return (float) $defaultAmount;
        }

        // Varsayılan değer
        return 50.00;
    }

    /**
     * Aidat vade tarihini belirle
     */
    private function getDueDate($targetDate)
    {
        // Varsayılan olarak ayın 15'i
        return $targetDate->copy()->day(15);
    }
}
