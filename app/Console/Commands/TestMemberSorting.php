<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Due;

class TestMemberSorting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:member-sorting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test member sorting for PDF reports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Üye sıralaması test ediliyor...');

        // 2024 ödeme yapan üyeleri al (rapor mantığı ile aynı)
        $paidDues = Due::select('id', 'member_id', 'year', 'month', 'amount', 'status')
            ->where('year', 2024)
            ->where('status', 'paid')
            ->get();

        $memberIds = $paidDues->pluck('member_id')->unique();
        
        $this->info("Toplam ödeme yapan üye: {$memberIds->count()}");
        $this->newLine();

        // Sıralama testi
        $allMembers = Member::select('id', 'name', 'surname', 'member_no')
            ->whereIn('id', $memberIds)
            ->orderBy('surname', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $this->info('=== İLK 20 ÜYE (Soyisim, İsim sıralaması) ===');
        foreach ($allMembers->take(20) as $member) {
            $this->line("{$member->surname}, {$member->name} (ID: {$member->id})");
        }

        $this->newLine();
        $this->info('✅ Sıralama: Önce soyisim (A-Z), sonra isim (A-Z)');
        $this->info('📄 PDF\'de de aynı sıralama kullanılacak');

        return Command::SUCCESS;
    }
}
