<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\Due;

class DeleteAllPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:delete-all {--force : Force delete without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tüm ödemeleri ve aidatları siler';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $paymentCount = Payment::count();
        $dueCount = Due::count();

        $this->info("Mevcut Durum:");
        $this->info("- Toplam Ödeme: {$paymentCount}");
        $this->info("- Toplam Aidat: {$dueCount}");

        if (!$this->option('force')) {
            if (!$this->confirm('TÜM ödemeleri ve aidatları silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')) {
                $this->info('İşlem iptal edildi.');
                return;
            }
        }

        $this->info('Silme işlemi başlatılıyor...');

        // Foreign key kontrollerini geçici olarak devre dışı bırak
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Önce pivot tabloları temizle
        $this->info('Pivot tablolar temizleniyor...');
        \DB::table('payment_due')->truncate();
        $this->info('✓ Pivot tablolar temizlendi.');

        // Sonra ödemeleri sil
        $this->info('Ödemeler siliniyor...');
        Payment::truncate();
        $this->info('✓ Ödemeler silindi.');

        // Sonra aidatları sil
        $this->info('Aidatlar siliniyor...');
        Due::truncate();
        $this->info('✓ Aidatlar silindi.');

        // Foreign key kontrollerini tekrar aktif et
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Aidat durumlarını sıfırla (eğer üyeler varsa)
        $this->info('Üye aidat durumları sıfırlanıyor...');
        \DB::table('members')->update([
            'monthly_dues' => 0
        ]);
        $this->info('✓ Üye aidat durumları sıfırlandı.');

        $this->info('🎉 Tüm ödemeler ve aidatlar başarıyla silindi!');
        $this->info('Sistem temizlendi ve yeni aidatlar oluşturulabilir.');
    }
}
