<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Due;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class CheckProblematicDues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:problematic-dues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check dues with status=paid but no payment records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Problematik aidatları kontrol ediliyor...');

        // Status = 'paid' ama ödeme kaydı olmayan aidatlar
        $problematicDues = Due::where('status', 'paid')
            ->whereDoesntHave('payments')
            ->whereDoesntHave('paymentDues')
            ->with('member')
            ->get();

        $this->info('Toplam problematik aidat sayısı: ' . $problematicDues->count());
        $this->line('==================================');

        // Üye bazında grupla
        $memberStats = [];
        foreach ($problematicDues as $due) {
            $memberKey = $due->member->name . ' ' . $due->member->surname . ' (' . $due->member->member_no . ')';
            if (!isset($memberStats[$memberKey])) {
                $memberStats[$memberKey] = [];
            }
            $memberStats[$memberKey][] = $due->year . '-' . $due->month;
        }

        $this->info('Etkilenen üye sayısı: ' . count($memberStats));
        $this->line('==================================');

        // Her üye için problematik aidatları listele
        foreach ($memberStats as $member => $months) {
            $this->line($member . ': ' . implode(', ', $months) . ' (' . count($months) . ' aidat)');
        }

        // İlk 10 detay
        $this->line('');
        $this->info('=== İLK 10 DETAY ===');
        foreach ($problematicDues->take(10) as $due) {
            $this->line("ID: {$due->id}, Üye: {$due->member->name} {$due->member->surname} ({$due->member->member_no}), Dönem: {$due->year}-{$due->month}, Status: {$due->status}");
        }

        return Command::SUCCESS;
    }
}
