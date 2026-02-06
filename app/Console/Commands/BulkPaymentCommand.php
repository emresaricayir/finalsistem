<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Due;
use App\Models\Payment;
use Carbon\Carbon;

class BulkPaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:bulk-payment
                            {month : Ay (1-12)}
                            {year : Yıl (2025)}
                            {--members= : Üye isimleri (virgülle ayrılmış)}
                            {--method=cash : Ödeme yöntemi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Belirtilen üyelerin belirli ay aidatlarını toplu olarak ödenmiş olarak işaretler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = (int) $this->argument('month');
        $year = (int) $this->argument('year');
        $method = $this->option('method');
        $membersInput = $this->option('members');

        if (!$membersInput) {
            $this->error('Üye listesi gerekli! --members="İsim1,İsim2" formatında girin.');
            return 1;
        }

        // Üye isimlerini parse et
        $memberNames = array_map('trim', explode(',', $membersInput));

        $this->info("İşlem başlatılıyor...");
        $this->info("Ay: {$month}, Yıl: {$year}, Yöntem: {$method}");
        $this->info("Üye sayısı: " . count($memberNames));

        $processedCount = 0;
        $errorCount = 0;

        foreach ($memberNames as $memberName) {
            try {
                // Üyeyi bul (isim ve soyisim ile)
                $nameParts = explode(' ', trim($memberName));
                if (count($nameParts) < 2) {
                    $this->warn("Geçersiz isim formatı: {$memberName}");
                    $errorCount++;
                    continue;
                }

                $surname = $nameParts[0];
                $name = implode(' ', array_slice($nameParts, 1));

                $member = Member::where('surname', $surname)
                    ->where('name', $name)
                    ->where('status', 'active')
                    ->first();

                if (!$member) {
                    $this->warn("Üye bulunamadı: {$memberName}");
                    $errorCount++;
                    continue;
                }

                // Bu üyenin belirtilen ay/yıl aidatını bul
                $due = Due::where('member_id', $member->id)
                    ->whereYear('due_date', $year)
                    ->whereMonth('due_date', $month)
                    ->where('status', '!=', 'paid')
                    ->first();

                if (!$due) {
                    $this->warn("Aidat bulunamadı: {$memberName} - {$month}/{$year}");
                    $errorCount++;
                    continue;
                }

                // ÖNEMLİ: Duplicate kontrolü - Bu aidat zaten ödenmiş mi?
                if (Payment::isDueAlreadyPaid($due->id)) {
                    $this->warn("⚠️  Aidat ID {$due->id} zaten ödenmiş, atlanıyor: {$memberName}");
                    $errorCount++;
                    continue;
                }

                // ÖNEMLİ: Bu üye için aynı ay/yıl için başka bir ödeme var mı?
                if (Payment::hasMemberPaidForMonth($member->id, $year, $month)) {
                    $this->warn("⚠️  {$year}-{$month} zaten ödenmiş, atlanıyor: {$memberName}");
                    $errorCount++;
                    continue;
                }

                // Ödeme kaydı oluştur
                $payment = Payment::create([
                    'member_id' => $member->id,
                    'amount' => $due->amount,
                    'payment_method' => $method,
                    'payment_date' => now(),
                    'receipt_no' => 'BULK-' . now()->format('YmdHis') . '-' . $member->id,
                    'description' => "Toplu ödeme - {$year} yılı {$month}. ay",
                    'recorded_by' => 1, // Admin user ID
                ]);

                // Aidat durumunu güncelle
                $due->update([
                    'status' => 'paid',
                    'paid_date' => now(),
                ]);

                // Payment-due ilişkisini kur
                $payment->dues()->attach($due->id, [
                    'amount' => $due->amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->info("✓ İşlendi: {$memberName} - {$due->amount} TL");
                $processedCount++;

            } catch (\Exception $e) {
                $this->error("Hata ({$memberName}): " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("\n=== ÖZET ===");
        $this->info("Başarılı: {$processedCount}");
        $this->info("Hatalı: {$errorCount}");
        $this->info("Toplam: " . count($memberNames));

        return 0;
    }
}
