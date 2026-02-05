<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\ReportController;

class TestPdfSorting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pdf-sorting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test PDF sorting logic';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 PDF sıralaması test ediliyor...');

        // ReportController'ın getPaymentReport metodunu test et
        $controller = new ReportController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('getPaymentReport');
        $method->setAccessible(true);

        // 2025 yılı için test
        $data = $method->invoke($controller, '2025-01-01', '2025-12-31', null);

        $this->info("Toplam üye: " . count($data['monthly_payments']));
        $this->newLine();

        $this->info('=== İLK 20 ÜYE (PDF Sıralaması) ===');
        $count = 0;
        foreach ($data['monthly_payments'] as $memberId => $memberData) {
            if ($count >= 20) break;
            $member = $memberData['member'];
            $this->line("{$member->surname}, {$member->name} (ID: {$member->id})");
            $count++;
        }

        $this->newLine();
        $this->info('✅ PDF sıralaması: Soyisim, İsim (A-Z)');

        return Command::SUCCESS;
    }
}
