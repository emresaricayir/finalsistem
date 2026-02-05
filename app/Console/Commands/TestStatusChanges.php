<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Due;
use App\Services\DuesValidationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class TestStatusChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:test-status-changes {--member-id= : Belirli bir üye ID\'si} {--sample-size=5 : Test edilecek örnek üye sayısı}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Üye durumu değişikliklerini test et ve doğrula';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🛡️ Üye Durumu Değişiklikleri Test Ediliyor...');
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
        $this->testMemberStatusChanges($member);
    }

    private function testSampleMembers($sampleSize)
    {
        $this->info("📊 {$sampleSize} örnek üye test ediliyor...");

        // Farklı durumlardan örnekler al
        $members = Member::whereIn('status', ['active', 'inactive', 'suspended'])
            ->limit($sampleSize)
            ->get();

        if ($members->isEmpty()) {
            $this->warn('Test edilecek üye bulunamadı');
            return;
        }

        foreach ($members as $member) {
            $this->testMemberStatusChanges($member);
            $this->newLine();
        }
    }

    private function testMemberStatusChanges(Member $member)
    {
        $this->line("• Üye: {$member->name} {$member->surname}");
        $this->line("• Mevcut Durum: {$member->status}");
        $this->line("• Üyelik Tarihi: " . Carbon::parse($member->membership_date)->format('d.m.Y'));

        // Mevcut aidat durumu
        $dues = $member->dues()->get();
        $this->line("• Mevcut Aidat Sayısı: {$dues->count()}");

        if ($dues->count() > 0) {
            foreach ($dues->groupBy('status') as $status => $statusDues) {
                $this->line("  - {$status}: {$statusDues->count()}");
            }
        }

        // Farklı durum değişikliklerini test et
        $statuses = ['active', 'inactive', 'suspended'];

        foreach ($statuses as $newStatus) {
            if ($newStatus !== $member->status) {
                $this->testStatusChange($member, $newStatus);
            }
        }
    }

    private function testStatusChange(Member $member, string $newStatus)
    {
        $this->line("  🔄 {$member->status} → {$newStatus}:");

        // Status değişikliği validation
        $validation = DuesValidationService::validateStatusChange($member, $newStatus, $member->status);

        if ($validation['is_valid']) {
            $this->info("    ✅ Geçerli");

            if ($validation['action_required']) {
                $this->line("    📋 Gerekli İşlem: {$validation['action_required']}");
            }

            if (!empty($validation['warnings'])) {
                foreach ($validation['warnings'] as $warning) {
                    $this->warn("    ⚠️  {$warning}");
                }
            }
        } else {
            $this->error("    ❌ Geçersiz");
            foreach ($validation['errors'] as $error) {
                $this->error("      • {$error}");
            }
        }

        // Durum değişikliği sonrası kontrol
        $postChangeCheck = DuesValidationService::checkDuesAfterStatusChange($member, $newStatus);

        if ($postChangeCheck['has_issues']) {
            $this->warn("    🚨 Durum değişikliği sonrası sorunlar:");
            foreach ($postChangeCheck['issues'] as $issue) {
                $this->warn("      • {$issue}");
            }
        } else {
            $this->info("    ✅ Durum değişikliği sonrası sorun yok");
        }

        // Beklenen davranış kontrolü
        $this->checkExpectedBehavior($member, $newStatus);
    }

    private function checkExpectedBehavior(Member $member, string $newStatus)
    {
        $membershipDate = Carbon::parse($member->membership_date);
        $referenceDate = \App\Services\DuesValidationService::getReferenceDate();

        if ($newStatus === 'active') {
            // Aktif hale geldiğinde aidatların nasıl olması gerektiği
            $expectedStartDate = $membershipDate->lt($referenceDate)
                ? $referenceDate->copy()->startOfMonth()
                : $membershipDate->copy()->startOfMonth();

            $this->line("    📅 Beklenen başlangıç tarihi: {$expectedStartDate->format('d.m.Y')}");

            // Gelecekteki aidatlar var mı?
            $futureDues = $member->dues()
                ->where('due_date', '>', now())
                ->count();

            if ($futureDues === 0) {
                $this->warn("    ⚠️  Aktif üyenin gelecekteki aidatı yok");
            } else {
                $this->info("    ✅ Aktif üyenin {$futureDues} gelecekteki aidatı var");
            }
        } else {
            // Pasif/askıya alındığında gelecekteki aidatların askıya alınması gerekiyor
            $futureDues = $member->dues()
                ->where('due_date', '>', now())
                ->whereIn('status', ['pending', 'overdue'])
                ->count();

            if ($futureDues > 0) {
                $this->warn("    ⚠️  Pasif/askıya alınmış üyenin {$futureDues} gelecekteki aidatı var");
            } else {
                $this->info("    ✅ Pasif/askıya alınmış üyenin gelecekteki aidatı yok");
            }
        }
    }
}
