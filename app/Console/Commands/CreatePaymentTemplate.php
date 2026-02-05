<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CreatePaymentTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:create-template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Excel template for payment imports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            [
                'uye_numarasi' => 'Mitglied001',
                'yil' => '2025',
                'ay' => '1',
                'tutar' => '30.00',
                'odeme_tarihi' => '15.01.2025',
                'odeme_yontemi' => 'nakit',
                'aciklama' => 'Ocak ayı aidat ödemesi'
            ],
            [
                'uye_numarasi' => 'Mitglied002',
                'yil' => '2025',
                'ay' => '1',
                'tutar' => '25.00',
                'odeme_tarihi' => '20.01.2025',
                'odeme_yontemi' => 'banka',
                'aciklama' => 'Ocak ayı aidat ödemesi'
            ],
            [
                'uye_numarasi' => 'Mitglied003',
                'yil' => '2024',
                'ay' => '12',
                'tutar' => '30.00',
                'odeme_tarihi' => '10.12.2024',
                'odeme_yontemi' => 'kredi karti',
                'aciklama' => 'Aralık ayı aidat ödemesi'
            ],
        ];

        $export = new class($data) implements FromArray, WithHeadings {
            private $data;

            public function __construct($data) {
                $this->data = $data;
            }

            public function array(): array {
                return $this->data;
            }

            public function headings(): array {
                return [
                    'uye_numarasi',
                    'yil',
                    'ay',
                    'tutar',
                    'odeme_tarihi',
                    'odeme_yontemi',
                    'aciklama'
                ];
            }
        };

        $filename = 'odemeler_template_' . date('Y-m-d_H-i-s') . '.xlsx';
        $path = storage_path('app/public/templates/' . $filename);

        // Ensure directory exists
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        Excel::store($export, 'public/templates/' . $filename);

        $this->info("Ödeme template'i oluşturuldu: {$filename}");
        $this->info("Dosya konumu: storage/app/public/templates/{$filename}");

        return 0;
    }
}
