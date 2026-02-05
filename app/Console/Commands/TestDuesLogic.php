<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Due;
use App\Services\DuesValidationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TestDuesLogic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:test-logic {--member-id= : Belirli bir üye ID\'si} {--sample-size=10 : Test edilecek örnek üye sayısı}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aidat oluşturma mantığını test et ve doğrula';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🛡️ Aidat Mantığı Test Ediliyor...');
        $this->newLine();
        $this->warn('⚠️  Bu command sadece geliştirme ortamında kullanılmalıdır!');
        $this->newLine();

        $memberId = $this->option('member-id');
        $sampleSize = (int) $this->option('sample-size');

        if ($memberId) {
            $this->testSpecificMember($memberId);
        } else {
            $this->testSampleMembers($sampleSize);
        }

        $this->newLine();
        $this->info('✅ Test tamamlandı!');

        return 0;
    }

    private function testSpecificMember($memberId)
    {
        $member = Member::find($memberId);

        if (!$member) {
            $this->error("Üye bulunamadı: {$memberId}");
            return;
        }

        $this->info("🔍 Üye Test Ediliyor: {$member->name} {$member->surname} (ID: {$member->id})");
        $this->testMemberLogic($member);
    }

    private function testSampleMembers($sampleSize)
    {
        $this->info("📊 {$sampleSize} örnek üye test ediliyor...");

        // Farklı üyelik tarihlerinden örnekler al
        $members = Member::whereIn('membership_date', [
            '2024-01-01',
            '2024-06-15',
            '2024-12-31',
            '2025-01-01',
            '2025-06-15',
            '2025-12-31'
        ])->limit($sampleSize)->get();

        if ($members->isEmpty()) {
            $this->warn('Test edilecek üye bulunamadı');
            return;
        }

        foreach ($members as $member) {
            $this->testMemberLogic($member);
            $this->newLine();
        }
    }

    private function testMemberLogic(Member $member)
    {
        $membershipDate = Carbon::parse($member->membership_date);
        $referenceDate = \App\Services\DuesValidationService::getReferenceDate();

        $this->line("• Üyelik Tarihi: {$membershipDate->format('d.m.Y')}");

        // Validation test
        $validation = DuesValidationService::validateDuesCreationLogic($member);

        if ($validation['is_valid']) {
            $this->info("  ✅ Mantık geçerli");
            $this->line("  📅 Başlangıç Tarihi: {$validation['start_date']->format('d.m.Y')}");
            $this->line("  🧠 Uygulanan Mantık: {$validation['logic_applied']}");

            if (!empty($validation['warnings'])) {
                foreach ($validation['warnings'] as $warning) {
                    $this->warn("  ⚠️  {$warning}");
                }
            }
        } else {
            $this->error("  ❌ Mantık geçersiz");
            foreach ($validation['errors'] as $error) {
                $this->error("    • {$error}");
            }
        }

        // Critical conditions test
        $critical = DuesValidationService::checkCriticalConditions($member);
        if ($critical['has_critical_issues']) {
            $this->warn("  🚨 Kritik durumlar:");
            foreach ($critical['issues'] as $issue) {
                $this->warn("    • {$issue}");
            }
        }

        // Existing dues conflicts test
        $startDate = $validation['start_date'] ?? $membershipDate->copy()->startOfMonth();
        $conflicts = DuesValidationService::checkExistingDuesConflicts($member, $startDate);

        if ($conflicts['has_conflicts']) {
            $this->warn("  ⚔️  Mevcut aidat çakışmaları:");
            foreach ($conflicts['summary'] as $status => $count) {
                $this->warn("    • {$status}: {$count} aidat");
            }
        } else {
            $this->info("  ✅ Mevcut aidat çakışması yok");
        }

        // Expected vs Actual logic test
        $expectedStartDate = $membershipDate->lt($referenceDate)
            ? $referenceDate->copy()->startOfMonth()
            : $membershipDate->copy()->startOfMonth();

        $actualStartDate = $validation['start_date'] ?? null;

        if ($actualStartDate && $expectedStartDate->eq($actualStartDate)) {
            $this->info("  ✅ Beklenen mantık doğru uygulanmış");
        } else {
            $this->error("  ❌ Beklenen mantık yanlış uygulanmış");
            $this->line("    Beklenen: {$expectedStartDate->format('d.m.Y')}");
            $this->line("    Gerçek: " . ($actualStartDate ? $actualStartDate->format('d.m.Y') : 'null'));
        }
    }
}
