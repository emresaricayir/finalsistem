<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\Due;
use App\Models\Payment;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MarkPaidDues2 extends Command
{
    protected $signature = 'dues:mark-paid-batch2';
    protected $description = 'Mark specific dues as paid for second batch of members';

    public function handle()
    {
        $this->info('💰 İkinci Grup Aidatlar Ödendi Olarak İşleniyor...');
        $this->newLine();

        // Ödenecek aidatlar (2025 yılı - Tüm aylar 1-12)
        $memberNames = [
            'Kadir Ambarkütük', 'Enes Anbarkütük', 'Tanya Angelastri', 'Mikail Annac', 'Mahmut Aramaz',
            'Senol Arapi', 'Bilal Ayar', 'Mustafa Bagci', 'Davut Bagci', 'Musa Bagci',
            'Ümüt Bakir', 'Özgür Bahadin', 'Lütfü Baki', 'Özdemir Bakir', 'Senay Bakir',
            'Özgün Bakir', 'Hakan Basuslu', 'Alpaslan Basuslu', 'Enver Basuslu', 'Hanife Basuslu',
            'Hüseyin Basuslu', 'Sükriye Basuslu', 'Seyit Basuslu', 'Aysegül Bekdemir', 'Kemal Bicakci',
            'Feyzullah Bilgic', 'Bilal Bülürce', 'Erkan Bülürce', 'Tayfun Bülürce', 'Dilek Can',
            'Mehmet Can', 'Arslan Pasa Cetiner', 'Ferhat Cevizkaya', 'Ahmet Ceylan', 'Bilal Ercan Ceylan',
            'Umut Ceylan', 'Eren Cicek', 'Mehmet Fatih Cicek', 'S.Ahmet Cil', 'Kenan Cimsir',
            'Zeliha Arzu Cömertler', 'Mehmet Hakan Colban', 'Hüseyin Colban', 'Bülent Cürt', 'Hava Cürt',
            'Aytekin Dag', 'Kazim Batu Dalfesoglu', 'Isa Dilmac', 'Mehmet Dogan', 'Okan Dinckol',
            'Mehmet Dogan', 'Ekrem Ercici', 'Ahmet Erdal', 'Murat Erden', 'Saban Ahmed Evgen',
            'Samed Muhammed Fidan', 'Kadriye Genc', 'Hakan Geyik', 'Emre Görkem', 'Kazim Gürkan',
            'Halil-Ibrahim Gülle', 'Necdet Gültekin', 'Cevat Gün', 'Tülay Gümüs', 'Ahmet Güven',
            'Hakki Güven', 'Isat Elvir Ibishi', 'Gülcan Ince', 'Sahin Ince', 'Ali Isik',
            'Muharrem Kaplan', 'Cenap Kahriman', 'Nuren Kahriman', 'Hasan Kahraman', 'Kerim Kambir',
            'Yasar Kambir', 'Mine Kambir', 'Bedriye Kandemir', 'Ahmet Karalar', 'Necmettin Ilker Karasulu',
            'Murat Kardas', 'Mustafa Kaya', 'Filiz Kayatas', 'Ömer Kayatas', 'Sabri Keklik',
            'Anil Kökce', 'Cemal Korkmaz', 'Bilal Kocoglu', 'Özkan Kovanci', 'Sinan Kovanci',
            'Nijazi Krasnigi', 'Celal Külah', 'Gökhan Kurt', 'Damla Kurt', 'Ahmet Kurt',
            'Gül Kurt', 'Aydin Kutlu', 'Günter Lackmann', 'Osman Memis', 'Murat Mingir',
            'Emrah Özberk', 'Ali Özberk', 'Erdal Özdemir', 'Halim Özdemir', 'Selim Özdemir',
            'Yusuf Özdemir', 'Halit Özel', 'Cahit Özkul', 'Ercan Özmen', 'Perigial Ogultarhan',
            'Fatma Orman', 'Levent Orman', 'Ahmet Taha Palali', 'Hilmi Palali', 'Oguz Palali',
            'Arzu Palali', 'Kadriye Palali', 'Ismehan Parlak', 'Münür Pesen', 'Vefa Pinargil',
            'Emre Pinargil', 'Furkan Pinargil', 'Gülay Pinargil', 'Cengiz Polat', 'Sakir Polat',
            'Deniz Polat', 'Ergün Polat', 'Resul Rasimi', 'Burcu Nur Saf', 'Eda Saf',
            'Enes Süleyman Saf', 'Saadettin Saf', 'Cengiz Saf', 'Hamza Saf', 'Ilhami Saf',
            'Miray Tuana Saf', 'Seda Saf', 'Tahir Saf', 'Abdullah Saglam', 'Özkan Salcan',
            'Furkan Sarac', 'Riza - Yasin Sarac', 'Oguzhan Sari', 'Mertel Sentürklü', 'Gönül Sipahi',
            'Resul Sipahi', 'Hikmet Sönmez', 'Ersin Targan', 'Yalcin Tas', 'Erdal/Sibel Tasci',
            'Emre Tastan', 'Murat Temiz', 'Özer Topcubasi', 'Hilal Türkan', 'Mesut Türkan',
            'Gökhan Ucar', 'Elyasa-Mübin Ünal', 'Ahmet Ünver', 'Münevver Ünver', 'Mustafa Ünver',
            'Cengiz Uyanik', 'Fatih Uyanik', 'Serdar Yaban', 'Erkan Yalmanci', 'Halime Yavuz',
            'Yusuf Yesilyurt', 'Turgay Yilmaz', 'Hüsniye Yilmaz', 'Osman Yilmaz', 'Metin Yüregir',
            'Ibrahim Zandolu', 'Ertan Zorlu'
        ];

        $year = 2025;
        $months = [1, 2, 3, 4, 5, 6, 7, 8, 9]; // Tüm aylar
        $totalProcessed = 0;
        $totalAmount = 0;
        $notFoundCount = 0;

        DB::transaction(function () use ($memberNames, $months, $year, &$totalProcessed, &$totalAmount, &$notFoundCount) {
            foreach ($memberNames as $fullName) {
                $this->info("🔍 İşleniyor: {$fullName}");

                // İsmi parçala
                $parts = explode(' ', trim($fullName));

                if (count($parts) < 2) {
                    $this->error("   ❌ Geçersiz isim formatı: {$fullName}");
                    $notFoundCount++;
                    continue;
                }

                $firstName = $parts[0];
                $lastName = implode(' ', array_slice($parts, 1));

                // Üyeyi bul - hem düz hem ters sırada ara
                $member = Member::where(function($q) use ($firstName, $lastName) {
                    $q->where(function($query) use ($firstName, $lastName) {
                        $query->where('name', $firstName)->where('surname', $lastName);
                    })->orWhere(function($query) use ($firstName, $lastName) {
                        $query->where('name', $lastName)->where('surname', $firstName);
                    });
                })->first();

                if (!$member) {
                    $this->error("   ❌ Üye bulunamadı: {$fullName}");
                    $notFoundCount++;
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
                        continue;
                    }

                    // Ödeme kaydı oluştur
                    $payment = Payment::create([
                        'member_id' => $member->id,
                        'amount' => $due->amount,
                        'payment_method' => $member->payment_method ?? 'bank_transfer',
                        'payment_date' => Carbon::create($year, $month, 1)->endOfMonth(),
                        'recorded_by' => 1, // Admin user ID
                        'notes' => 'Toplu ödeme kaydı (Batch 2) - Manuel giriş',
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
                }

                if ($paidCount > 0) {
                    $this->info("   📊 {$paidCount} ay ödendi: " . number_format($memberTotal, 2) . " €");
                    $totalProcessed += $paidCount;
                    $totalAmount += $memberTotal;
                }

                $this->newLine();
            }
        });

        $this->newLine();
        $this->info('═══════════════════════════════════════════════════');
        $this->info('✅ İŞLEM TAMAMLANDI (BATCH 2)');
        $this->info('═══════════════════════════════════════════════════');
        $this->info("   👥 İşlenen Üye Sayısı: " . (count($memberNames) - $notFoundCount));
        $this->info("   ❌ Bulunamayan Üye Sayısı: {$notFoundCount}");
        $this->info("   📅 İşlenen Aidat Sayısı: {$totalProcessed}");
        $this->info("   💰 Toplam Tutar: " . number_format($totalAmount, 2) . " €");
        $this->newLine();

        return Command::SUCCESS;
    }
}



