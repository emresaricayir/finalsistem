<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdatePaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Eski enum değerlerini yeni değerlere dönüştür
        \DB::table('members')->where('payment_method', 'direct_debit')->update(['payment_method' => 'bank_transfer']);
        \DB::table('members')->where('payment_method', 'standing_order')->update(['payment_method' => 'bank_transfer']);

        $this->command->info('Payment method values updated successfully!');
    }
}
