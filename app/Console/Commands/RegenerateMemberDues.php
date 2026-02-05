<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Due;
use App\Models\Payment;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RegenerateMemberDues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:regenerate {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tüm üyelerin aidatlarını yeniden oluşturur (01.01.2025 öncesi üyeler için 01.01.2025\'ten, sonrası için üyelik tarihinden itibaren 10 yıllık)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            $this->warn('⚠️  DİKKAT: Bu işlem tüm mevcut aidatları ve ödemeleri silecektir!');
            if (!$this->confirm('Devam etmek istediğinizden emin misiniz?')) {
                $this->info('İşlem iptal edildi.');
                return 0;
            }
        }

        $this->info('🔄 Aidat yenileme işlemi başlatılıyor...');

        DB::beginTransaction();

        try {
            // 1. Tüm mevcut aidatları ve ödemeleri sil
            $this->info('📝 Mevcut aidatlar ve ödemeler siliniyor...');
            $deletedPayments = Payment::count();
            $deletedDues = Due::count();

            Payment::query()->forceDelete();
            Due::query()->forceDelete();

            $this->info("   ✓ {$deletedPayments} ödeme silindi");
            $this->info("   ✓ {$deletedDues} aidat silindi");

            // 2. Aktif üyeleri al
            $members = Member::where('status', 'active')->get();
            $this->info("📊 {$members->count()} aktif üye için aidatlar oluşturuluyor...");

            $cutoffDate = \App\Services\DuesValidationService::getReferenceDate();
            $progressBar = $this->output->createProgressBar($members->count());
            $progressBar->start();

            $totalDuesCreated = 0;

            foreach ($members as $member) {
                $membershipDate = Carbon::parse($member->membership_date);

                // Başlangıç tarihini belirle: Her zaman üyelik tarihinden başla
                $startDate = $membershipDate->copy()->startOfMonth();

                // 10 yıllık aidat oluştur (120 ay)
                $duesCreated = 0;
                for ($i = 0; $i < 120; $i++) {
                    $dueDate = $startDate->copy()->addMonths($i);

                    // Her ayın son günü vade tarihi
                    $dueDateForMonth = $dueDate->copy()->endOfMonth();

                    // Aidat durumunu belirle
                    $status = 'pending';
                    if ($dueDateForMonth->isPast()) {
                        $status = 'overdue';
                    }

                    Due::create([
                        'member_id' => $member->id,
                        'amount' => $member->monthly_dues,
                        'due_date' => $dueDateForMonth,
                        'month' => $dueDate->month,
                        'year' => $dueDate->year,
                        'status' => $status,
                        'description' => $dueDate->translatedFormat('F Y') . ' Aidatı'
                    ]);

                    $duesCreated++;
                }

                $totalDuesCreated += $duesCreated;
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);

            DB::commit();

            // Özet bilgiler
            $this->info('✅ İşlem başarıyla tamamlandı!');
            $this->newLine();
            $this->table(
                ['Metrik', 'Değer'],
                [
                    ['İşlenen Üye Sayısı', $members->count()],
                    ['Oluşturulan Toplam Aidat', number_format($totalDuesCreated)],
                    ['Üye Başına Aidat', '120 ay (10 yıl)'],
                    ['Silinen Ödeme', number_format($deletedPayments)],
                    ['Silinen Aidat', number_format($deletedDues)],
                ]
            );

            $this->newLine();
            $this->info('📅 Aidat başlangıç tarihleri:');
            $this->info('   • 01.01.2025 öncesi üyeler: 01.01.2025\'ten başladı');
            $this->info('   • 01.01.2025 sonrası üyeler: Üyelik tarihinden başladı');

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Hata oluştu: ' . $e->getMessage());
            $this->error('İşlem geri alındı.');
            return 1;
        }
    }
}
