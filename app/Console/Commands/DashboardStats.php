<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Payment;
use Illuminate\Console\Command;

class DashboardStats extends Command
{
    protected $signature = 'dashboard:stats';
    protected $description = 'Show dashboard statistics';

    public function handle()
    {
        $this->info('📊 Dashboard İstatistikleri');
        $this->newLine();

        // Üye istatistikleri
        $activeMembers = Member::where('status', 'active')->count();
        $monthlyDuesTotal = Member::where('status', 'active')->sum('monthly_dues');
        $yearlyExpected = $monthlyDuesTotal * 12;

        $this->line('🧑‍🤝‍🧑 <fg=cyan>Üye İstatistikleri:</>');
        $this->line("   • Aktif üye sayısı: <fg=green>{$activeMembers}</>");
        $this->line("   • Toplam aylık aidat: <fg=green>" . number_format($monthlyDuesTotal, 2) . " €</>");
        $this->line("   • Yıllık beklenen aidat (x12): <fg=yellow>" . number_format($yearlyExpected, 2) . " €</>");
        $this->newLine();

        // Gelir istatistikleri
        $paymentsIn2025 = Payment::whereYear('payment_date', 2025)->sum('amount');

        $paymentsFor2025Dues = Payment::whereHas('dues', function($query) {
            $query->where('year', 2025);
        })->sum('amount');

        $this->line('💰 <fg=cyan>Gelir İstatistikleri (2025):</>');
        $this->line("   • 2025'te KAYIT EDİLEN ödemeler: <fg=blue>" . number_format($paymentsIn2025, 2) . " €</>");
        $this->line("   • 2025 AIDATLARINA yapılan ödemeler: <fg=blue>" . number_format($paymentsFor2025Dues, 2) . " €</>");
        $this->newLine();

        // Karşılaştırma
        $this->line('📈 <fg=cyan>Karşılaştırma:</>');
        $difference = $paymentsFor2025Dues - $yearlyExpected;
        $percentage = $yearlyExpected > 0 ? ($paymentsFor2025Dues / $yearlyExpected * 100) : 0;

        if ($difference >= 0) {
            $this->line("   • Beklenen aidatın <fg=green>%" . number_format($percentage, 1) . "</>'si tahsil edildi");
            $this->line("   • Beklenenin <fg=green>" . number_format(abs($difference), 2) . " € üzerinde</>");
        } else {
            $this->line("   • Beklenen aidatın <fg=yellow>%" . number_format($percentage, 1) . "</>'si tahsil edildi");
            $this->line("   • Beklenenin <fg=yellow>" . number_format(abs($difference), 2) . " € altında</>");
        }

        return Command::SUCCESS;
    }
}



