<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateAnnualLastschriftMembers extends Command
{
    protected $signature = 'members:update-annual-lastschrift';
    protected $description = 'XML\'deki yıllık Lastschrift üyelerini güncelle';

    public function handle()
    {
        $this->info('🔄 Yıllık Lastschrift üyeleri güncelleniyor...');

        // XML'den gelen isimler
        $names = [
            'Aydin Abdullah',
            'Aydin Necati',
            'Basuslu Aytekin',
            'Bekar Mert',
            'Bicakci Nursen',
            'Bicakci Sezgin',
            'Calabakan Aziz',
            'Cevizkaya Fatma Zehra',
            'Cevizkaya Ferhat',
            'Cuert Levent',
            'Dogan Öztas',
            'Ellik Hakan',
            'Erze Yildirm',
            'Görgülü Ergün',
            'Gürbüz Harun',
            'Gürbüz Ibrahim',
            'Isci Berkant',
            'Isci Metin',
            'Isik Ömer',
            'Kalkan Ercin',
            'Karaca Mikail',
            'Karaca Niyazi',
            'Kavsitli Oguzhan',
            'Kaya Mehmet',
            'Kayatas Ali',
            'Kocoglu Bilal',
            'Kücük Habib',
            'Polat Can Mehmet',
            'Saf Döndü',
            'Turan Salih',
        ];

        $updated = 0;
        $notFound = [];
        $alreadyAnnual = 0;

        DB::beginTransaction();

        try {
            foreach ($names as $fullName) {
                // İsmi ayır (son kelime soyad, geri kalanı ad)
                $parts = explode(' ', $fullName);

                if (count($parts) >= 2) {
                    $surname = array_pop($parts);
                    $name = implode(' ', $parts);

                    // Üyeyi bul - hem ad soyad hem soyad ad sırasını dene
                    $member = Member::where(function($query) use ($name, $surname) {
                        $query->where(function($q) use ($name, $surname) {
                            $q->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
                              ->whereRaw('LOWER(surname) = ?', [mb_strtolower($surname)]);
                        })->orWhere(function($q) use ($name, $surname) {
                            $q->whereRaw('LOWER(name) = ?', [mb_strtolower($surname)])
                              ->whereRaw('LOWER(surname) = ?', [mb_strtolower($name)]);
                        });
                    })->first();

                    if ($member) {
                        if ($member->payment_method === 'lastschrift_annual') {
                            $alreadyAnnual++;
                            $this->line("   ⚪ {$fullName} → Zaten yıllık");
                        } else {
                            $oldMethod = $member->payment_method;
                            $member->payment_method = 'lastschrift_annual';
                            $member->save();
                            $updated++;
                            $this->line("   ✓ {$fullName} → {$oldMethod} => lastschrift_annual");
                        }
                    } else {
                        $notFound[] = $fullName;
                        $this->line("   ❌ {$fullName} → Bulunamadı");
                    }
                } else {
                    $notFound[] = $fullName;
                    $this->line("   ❌ {$fullName} → İsim formatı hatalı");
                }
            }

            DB::commit();

            $this->newLine();
            $this->info('✅ İşlem tamamlandı!');
            $this->newLine();

            $this->table(
                ['Durum', 'Sayı'],
                [
                    ['Güncellenen', $updated],
                    ['Zaten Yıllık', $alreadyAnnual],
                    ['Bulunamayan', count($notFound)],
                    ['Toplam İşlenen', count($names)],
                ]
            );

            if (!empty($notFound)) {
                $this->newLine();
                $this->warn('⚠️  Bulunamayan üyeler:');
                foreach ($notFound as $name) {
                    $this->line("   • {$name}");
                }
            }

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Hata: ' . $e->getMessage());
            return 1;
        }
    }
}
