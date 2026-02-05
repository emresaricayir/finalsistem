<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Due;
use App\Models\Payment;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MarkPaidDues extends Command
{
    protected $signature = 'dues:mark-paid';
    protected $description = 'Mark specific dues as paid for Acikalin members';

    public function handle()
    {
        $this->info('💰 Aidatlar Ödendi Olarak İşleniyor...');
        $this->newLine();

        // Ödenecek aidatlar (2025 yılı - Tüm aylar 1-12)
        $payments = [
            'Cemal Acikalin' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Sait Acikgöz' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Segdin Agron' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mehmet Akbuga' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Niyazi Akgün' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'M Nurullah Aktürk' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Gülay Akyazi' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ibrahim Amber' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Canan Anbarkütük' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mehmet Artikarslan' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Senol Arapi' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Bilal Ayar' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Abdullah Aydin' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ahmet Aydin' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Necati Aydin' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Nehat Azizi' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Osman Bagci' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Aytekin Basuslu' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Filiz Basuslu' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Hasan Basuslu' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ibrahim Basuslu' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ibrahim Bayman' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mert Bekar' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Nursen Bicakci' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mesut Borazanci' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Aziz Calabakan' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Murat Can' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Fatma Zehra Cevizkaya' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ömer Cevizkaya' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ilhan Cicek' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Levent Cürt' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Süleyman Demirayak' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Secim Demirel' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Evyip Dirguti' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ahmet Ellik' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Hakan Ellik' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Yıldırım Erze' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ali Evgen' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Hüdai Fakioglu' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ali Vahdettin Fakioglu' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Arzu Fakioglu' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ergün Görgülü' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Dogan Gözegir' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Harun Gürbüz' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ibrahim Gürbüz' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ekrem Hyseni' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Berkant Isci' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Metin Isci' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ömer Isik' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Hüseyin Iscimen' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Selaettin Kadici' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ali Karaca' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mikail Karaca' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Niyazi Karaca' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mustafa Karakayali' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Haydar Kardas' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Necip Kardas' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Feyfun Kardas' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Utku Yüksel Kardas' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Yasemin Kardas' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Türkkan Kardas' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Sevgi Kardas' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Songül Kardas' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mehmet Kavsitli' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mehmet Kaya' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ali Kayatas' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Yüksel Keser' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Erkan Keteci' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ersan Keteci' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Merve Keteci' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mustafa Kilickaya' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Gökut Kovanci' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Neja Kücük' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Yunus Kücük' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Habib Kücük' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mustafa Memis' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Asim Naim' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Ahmet Narin' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Emre Öz' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Recep Özcelik' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Muhammet Ali Özdemir' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Dogan Öztas' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Nazir Palali' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Can Mehmet Polat' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Döndü Saf' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Hanefi Saf' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Sa Sai' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Camil Saribay' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mehmet Sarikaya' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Salah Shefik' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Sait Topcubasi' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Salih Turan' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Duran Türkan' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Balen Yılmaz' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Cağlar Yilmaz' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Nazi Yilmaz' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            'Mustafa Zandolu' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
        ];

        $year = 2025;
        $totalProcessed = 0;
        $totalAmount = 0;

        DB::transaction(function () use ($payments, $year, &$totalProcessed, &$totalAmount) {
            foreach ($payments as $fullName => $months) {
                $this->info("🔍 İşleniyor: {$fullName}");

                // İsmi parçala
                $parts = explode(' ', $fullName);
                $firstName = $parts[0];
                $lastName = $parts[1] ?? '';

                // Üyeyi bul
                $member = Member::where(function($q) use ($firstName, $lastName) {
                    $q->where('name', $firstName)->where('surname', $lastName);
                })->orWhere(function($q) use ($firstName, $lastName) {
                    $q->where('name', $lastName)->where('surname', $firstName);
                })->first();

                if (!$member) {
                    $this->error("   ❌ Üye bulunamadı: {$fullName}");
                    continue;
                }

                $this->comment("   ✓ Üye bulundu: {$member->name} {$member->surname} (#{$member->id})");
                $memberTotal = 0;
                $paidCount = 0;

                foreach ($months as $month) {
                    // İlgili aidatı bul
                    $due = Due::where('member_id', $member->id)
                        ->where('year', $year)
                        ->where('month', $month)
                        ->first();

                    if (!$due) {
                        $this->warn("   ⚠️  Aidat bulunamadı: {$year}-{$month}");
                        continue;
                    }

                    // Eğer zaten ödenmişse, geç
                    if ($due->status === 'paid') {
                        $this->comment("   ○ Zaten ödendi: {$year}-{$month}");
                        continue;
                    }

                    // Ödeme kaydı oluştur
                    $payment = Payment::create([
                        'member_id' => $member->id,
                        'amount' => $due->amount,
                        'payment_method' => $member->payment_method ?? 'bank_transfer',
                        'payment_date' => Carbon::create($year, $month, 1)->endOfMonth(),
                        'recorded_by' => 1, // Admin user ID
                        'notes' => 'Toplu ödeme kaydı - Manuel giriş',
                    ]);

                    // Ödemeyi aidatla ilişkilendir
                    $payment->dues()->attach($due->id, ['amount' => $due->amount]);

                    // Aidatı ödendi olarak işaretle
                    $due->update([
                        'status' => 'paid',
                        'paid_date' => $payment->payment_date,
                    ]);

                    $memberTotal += $due->amount;
                    $paidCount++;

                    $monthName = Carbon::create($year, $month, 1)->locale('tr')->monthName;
                    $this->info("   ✓ {$monthName} {$year}: {$due->amount} € ödendi");
                }

                if ($paidCount > 0) {
                    $this->info("   📊 Toplam: {$paidCount} ay, {$memberTotal} €");
                    $totalProcessed += $paidCount;
                    $totalAmount += $memberTotal;
                }

                $this->newLine();
            }
        });

        $this->newLine();
        $this->info('═══════════════════════════════════════════════════');
        $this->info('✅ İŞLEM TAMAMLANDI');
        $this->info('═══════════════════════════════════════════════════');
        $this->info("   📅 İşlenen Aidat Sayısı: {$totalProcessed}");
        $this->info("   💰 Toplam Tutar: " . number_format($totalAmount, 2) . " €");
        $this->newLine();

        return Command::SUCCESS;
    }
}

