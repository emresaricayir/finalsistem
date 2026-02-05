<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Due;
use App\Models\Payment;
use App\Models\PaymentLog;

class SystemHealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:health-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check overall system health and data integrity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏥 SİSTEM SAĞLIK KONTROLÜ');
        $this->info('========================');
        $this->newLine();

        // 1. Temel istatistikler
        $this->info('📊 TEMEL İSTATİSTİKLER');
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $totalDues = Due::count();
        $totalPayments = Payment::count();
        $totalLogs = PaymentLog::count();

        $this->line("Toplam Üye: {$totalMembers}");
        $this->line("Aktif Üye: {$activeMembers}");
        $this->line("Toplam Aidat: {$totalDues}");
        $this->line("Toplam Ödeme: {$totalPayments}");
        $this->line("Toplam Log: {$totalLogs}");
        $this->newLine();

        // 2. Problematik aidatlar
        $this->info('🔍 PROBLEMATİK AİDAT KONTROLÜ');
        $problematicDues = Due::where('status', 'paid')
            ->whereDoesntHave('payments')
            ->whereDoesntHave('paymentDues')
            ->count();

        if ($problematicDues === 0) {
            $this->info('✅ Problematik aidat bulunamadı - Sistem temiz!');
        } else {
            $this->error("❌ {$problematicDues} adet problematik aidat bulundu!");
        }
        $this->newLine();

        // 3. 2024 aidatları kontrol
        $this->info('📅 2024 AİDAT DURUMU');
        $dues2024 = Due::where('year', 2024)->count();
        $paidDues2024 = Due::where('year', 2024)->where('status', 'paid')->count();
        $unpaidDues2024 = Due::where('year', 2024)->where('status', 'unpaid')->count();

        $this->line("2024 Toplam Aidat: {$dues2024}");
        $this->line("2024 Ödenen: {$paidDues2024}");
        $this->line("2024 Ödenmemiş: {$unpaidDues2024}");

        $paidPercentage = $dues2024 > 0 ? round(($paidDues2024 / $dues2024) * 100, 1) : 0;
        $this->line("Ödeme Oranı: %{$paidPercentage}");
        $this->newLine();

        // 4. Payment kayıtları kontrolü
        $this->info('💰 ÖDEME KAYITLARI KONTROLÜ');
        $paymentsWithDues = Payment::whereNotNull('due_id')->count();
        $paymentsWithPivot = Payment::whereHas('dues')->count();
        $paymentsWithoutAnyDue = Payment::whereNull('due_id')->whereDoesntHave('dues')->count();

        $this->line("Eski sistem (due_id): {$paymentsWithDues}");
        $this->line("Yeni sistem (pivot): {$paymentsWithPivot}");
        $this->line("Aidat bağlantısı olmayan: {$paymentsWithoutAnyDue}");
        $this->newLine();

        // 5. Genel sistem durumu
        $this->info('🎯 GENEL DURUM');
        $issues = 0;

        if ($problematicDues > 0) {
            $this->error("⚠️  {$problematicDues} problematik aidat var");
            $issues++;
        }

        if ($paymentsWithoutAnyDue > 0) {
            $this->warn("⚠️  {$paymentsWithoutAnyDue} ödeme kaydı aidat bağlantısı olmadan");
            $issues++;
        }

        if ($issues === 0) {
            $this->info('🎉 SİSTEM SAĞLIKLI - Tüm kontroller başarılı!');
            $this->info('📊 Raporlar düzgün çalışacak');
        } else {
            $this->warn("⚠️  {$issues} adet sorun tespit edildi");
        }

        return Command::SUCCESS;
    }
}
