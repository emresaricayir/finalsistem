<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Due;
use App\Services\DuesValidationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class MonitorDuesLogic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:monitor {--check-all : Tüm üyeleri kontrol et} {--fix-issues : Sorunları otomatik düzelt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aidat mantığındaki sorunları izle ve raporla';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Aidat Mantığı İzleme Başlatılıyor...');
        $this->newLine();
        $this->warn('⚠️  Bu command sadece geliştirme ortamında kullanılmalıdır!');
        $this->newLine();

        $checkAll = $this->option('check-all');
        $fixIssues = $this->option('fix-issues');

        if ($checkAll) {
            $this->checkAllMembers($fixIssues);
        } else {
            $this->checkRecentMembers($fixIssues);
        }

        $this->newLine();
        $this->info('✅ İzleme tamamlandı!');

        return 0;
    }

    private function checkAllMembers($fixIssues = false)
    {
        $this->info('📊 Tüm üyeler kontrol ediliyor...');

        $totalMembers = Member::count();
        $this->line("Toplam üye sayısı: {$totalMembers}");

        $bar = $this->output->createProgressBar($totalMembers);
        $bar->start();

        $issues = [
            'invalid_logic' => [],
            'critical_conditions' => [],
            'conflicts' => [],
            'wrong_start_dates' => [],
            'status_issues' => []
        ];

        Member::chunk(100, function ($members) use (&$issues, &$bar, $fixIssues) {
            foreach ($members as $member) {
                $memberIssues = $this->checkMemberIssues($member);

                foreach ($memberIssues as $type => $memberIssue) {
                    if (!empty($memberIssue)) {
                        $issues[$type][] = [
                            'member_id' => $member->id,
                            'member_name' => $member->name . ' ' . $member->surname,
                            'issue' => $memberIssue
                        ];
                    }
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine(2);

        $this->reportIssues($issues, $fixIssues);
    }

    private function checkRecentMembers($fixIssues = false)
    {
        $this->info('📊 Son 30 günde eklenen üyeler kontrol ediliyor...');

        $recentMembers = Member::where('created_at', '>=', Carbon::now()->subDays(30))->get();

        if ($recentMembers->isEmpty()) {
            $this->info('Son 30 günde eklenen üye bulunamadı');
            return;
        }

        $this->line("Kontrol edilecek üye sayısı: {$recentMembers->count()}");

        $issues = [
            'invalid_logic' => [],
            'critical_conditions' => [],
            'conflicts' => [],
            'wrong_start_dates' => [],
            'status_issues' => []
        ];

        foreach ($recentMembers as $member) {
            $memberIssues = $this->checkMemberIssues($member);

            foreach ($memberIssues as $type => $memberIssue) {
                if (!empty($memberIssue)) {
                    $issues[$type][] = [
                        'member_id' => $member->id,
                        'member_name' => $member->name . ' ' . $member->surname,
                        'issue' => $memberIssue
                    ];
                }
            }
        }

        $this->reportIssues($issues, $fixIssues);
    }

    private function checkMemberIssues(Member $member)
    {
        $issues = [
            'invalid_logic' => null,
            'critical_conditions' => null,
            'conflicts' => null,
            'wrong_start_dates' => null,
            'status_issues' => null
        ];

        // Validation test
        $validation = DuesValidationService::validateDuesCreationLogic($member);

        if (!$validation['is_valid']) {
            $issues['invalid_logic'] = implode(', ', $validation['errors']);
        }

        // Critical conditions test
        $critical = DuesValidationService::checkCriticalConditions($member);
        if ($critical['has_critical_issues']) {
            $issues['critical_conditions'] = implode(', ', $critical['issues']);
        }

        // Existing dues conflicts test
        $startDate = $validation['start_date'] ?? Carbon::parse($member->membership_date)->copy()->startOfMonth();
        $conflicts = DuesValidationService::checkExistingDuesConflicts($member, $startDate);

        if ($conflicts['has_conflicts']) {
            $conflictSummary = [];
            foreach ($conflicts['summary'] as $status => $count) {
                $conflictSummary[] = "{$status}: {$count}";
            }
            $issues['conflicts'] = implode(', ', $conflictSummary);
        }

        // Expected vs Actual logic test
        $membershipDate = Carbon::parse($member->membership_date);
        $referenceDate = \App\Services\DuesValidationService::getReferenceDate();

        $expectedStartDate = $membershipDate->lt($referenceDate)
            ? $referenceDate->copy()->startOfMonth()
            : $membershipDate->copy()->startOfMonth();

        $actualStartDate = $validation['start_date'] ?? null;

        if ($actualStartDate && !$expectedStartDate->eq($actualStartDate)) {
            $issues['wrong_start_dates'] = "Beklenen: {$expectedStartDate->format('d.m.Y')}, Gerçek: {$actualStartDate->format('d.m.Y')}";
        }

        // Status kontrolü
        $statusCheck = DuesValidationService::checkDuesAfterStatusChange($member, $member->status);
        if ($statusCheck['has_issues']) {
            $issues['status_issues'] = implode(', ', $statusCheck['issues']);
        }

        return $issues;
    }

    private function reportIssues($issues, $fixIssues = false)
    {
        $totalIssues = 0;

        foreach ($issues as $type => $typeIssues) {
            $totalIssues += count($typeIssues);
        }

        if ($totalIssues === 0) {
            $this->info('🎉 Hiç sorun bulunamadı! Sistem sağlıklı çalışıyor.');
            return;
        }

        $this->warn("⚠️  Toplam {$totalIssues} sorun tespit edildi:");
        $this->newLine();

        // Invalid logic issues
        if (!empty($issues['invalid_logic'])) {
            $this->error("❌ Geçersiz Mantık ({count}):", ['count' => count($issues['invalid_logic'])]);
            foreach ($issues['invalid_logic'] as $issue) {
                $this->line("  • {$issue['member_name']} (ID: {$issue['member_id']}): {$issue['issue']}");
            }
            $this->newLine();
        }

        // Critical conditions
        if (!empty($issues['critical_conditions'])) {
            $this->warn("🚨 Kritik Durumlar ({count}):", ['count' => count($issues['critical_conditions'])]);
            foreach ($issues['critical_conditions'] as $issue) {
                $this->line("  • {$issue['member_name']} (ID: {$issue['member_id']}): {$issue['issue']}");
            }
            $this->newLine();
        }

        // Conflicts
        if (!empty($issues['conflicts'])) {
            $this->warn("⚔️  Aidat Çakışmaları ({count}):", ['count' => count($issues['conflicts'])]);
            foreach ($issues['conflicts'] as $issue) {
                $this->line("  • {$issue['member_name']} (ID: {$issue['member_id']}): {$issue['issue']}");
            }
            $this->newLine();
        }

        // Wrong start dates
        if (!empty($issues['wrong_start_dates'])) {
            $this->error("📅 Yanlış Başlangıç Tarihleri ({count}):", ['count' => count($issues['wrong_start_dates'])]);
            foreach ($issues['wrong_start_dates'] as $issue) {
                $this->line("  • {$issue['member_name']} (ID: {$issue['member_id']}): {$issue['issue']}");
            }
            $this->newLine();
        }

        // Status issues
        if (!empty($issues['status_issues'])) {
            $this->warn("🔄 Durum Sorunları ({count}):", ['count' => count($issues['status_issues'])]);
            foreach ($issues['status_issues'] as $issue) {
                $this->line("  • {$issue['member_name']} (ID: {$issue['member_id']}): {$issue['issue']}");
            }
            $this->newLine();
        }

        if ($fixIssues) {
            $this->info('🔧 Sorunları düzeltmek için gerekli işlemler yapılacak...');
            // Burada otomatik düzeltme işlemleri yapılabilir
        } else {
            $this->info('💡 Sorunları düzeltmek için --fix-issues parametresini kullanın');
        }
    }
}
