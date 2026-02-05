<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Due;
use Carbon\Carbon;

class RecalculateDuesStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:recalculate-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate dues status for all members based on due dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Aidat durumları yeniden hesaplanıyor...');

        $members = Member::with('dues')->get();
        $totalMembers = $members->count();
        $updatedDues = 0;

        $this->info("Toplam {$totalMembers} üye bulundu.");

        foreach ($members as $member) {
            $this->info("Üye işleniyor: {$member->name} {$member->surname} (ID: {$member->id})");

            foreach ($member->dues as $due) {
                $dueDate = Carbon::parse($due->due_date);
                $oldStatus = $due->status;

                // Status hesaplama: sadece geçmiş tarihlerdeki aidatlar gecikmiş olur
                if ($dueDate->isPast()) {
                    $newStatus = 'overdue';
                } else {
                    $newStatus = 'pending';
                }

                // Sadece ödenmemiş aidatları güncelle
                if ($due->status !== 'paid' && $oldStatus !== $newStatus) {
                    $due->status = $newStatus;
                    $due->save();
                    $updatedDues++;

                    $this->line("  - Aidat güncellendi: {$due->year}-{$due->month} ({$oldStatus} → {$newStatus})");
                }
            }
        }

        $this->info("İşlem tamamlandı!");
        $this->info("Toplam {$updatedDues} aidat güncellendi.");

        return Command::SUCCESS;
    }
}


