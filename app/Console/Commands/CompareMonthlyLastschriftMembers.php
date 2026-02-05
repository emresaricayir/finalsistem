<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CompareMonthlyLastschriftMembers extends Command
{
    protected $signature = 'members:compare-monthly-lastschrift';
    protected $description = 'Compare system monthly Lastschrift members with XML list';

    public function handle()
    {
        $this->info('📋 Aylık Lastschrift Üyeleri Karşılaştırması');
        $this->newLine();

        // XML'deki isimler (183 kişi)
        $xmlNames = [
            'Acikalin Erdogan', 'Acikalin Turan', 'Ademoski Kadri', 'Akbulut Oktay', 'Akgün Engin',
            'Akgün, Sadiye', 'Aktürk Abdurrahman', 'Albayrak Metin', 'Altun Mehmet', 'Altundas Osman',
            'Ambarkütükoglu Kadir', 'Anbarkütük Enes', 'Angelastri Tanya', 'Annac Israfil', 'Annac Mikail',
            'Annac Umud', 'Aramaz Mahmut', 'Artikarslan Yusuf', 'Aslan, Ferat', 'Ayazoglu, Ibrahim',
            'Aydin, Nihat', 'Bagci Davut', 'Bagci Musa', 'Bagci, Mustafa', 'Bahadin, Ayla',
            'Baki Lütfü', 'Bakir Kadir', 'Bakir Ümüt', 'Bakir, Özdemir', 'Bakir, Özgün',
            'Basuslu Düriye', 'Basuslu Ibrahim', 'Basuslu Mutlu', 'Basuslu, Alpaslan', 'Basuslu, Enver',
            'Basuslu, Hakan', 'Basuslu, Hüseyin', 'Basuslu, Seyit', 'Bayrak, Mehmet', 'Bekdemir Ayse',
            'Bicakci Kemal', 'Bilgic Feyzullah', 'Birdal Hüseyin', 'Biyik Davut', 'Bülürce Ekrem',
            'Bülürce Erkan', 'Bülürce Tayfun', 'Can, Mehmet', 'Cetiner, Arslan Pasa', 'Cevizkaya Ferhat',
            'Ceylan Ahmet', 'Ceylan Bilal Ercan', 'Ceylan Umut', 'Cicek Eren', 'Cicek Hasan',
            'Cicek Mehmet Fatih', 'Cil S.Ahmet', 'Cimsir Kenan', 'Cömertler, Zeliha Arzu', 'Colban, Hakan',
            'Colban, Hüseyin', 'Cürt Bülent', 'Cürt Hava', 'Dag Aytekin', 'Dalfesoglu Kazim-Batu',
            'Dalkilic Bayram', 'Demir Ali Osman', 'Dilmac Isa', 'Dinckol, Okan', 'Dogan Mehmet',
            'Dogan Mehmet', 'Ercici, Ekrem', 'Erdal Ahmet', 'Erden Murat', 'Ergin Ilhan',
            'Evgen Saban Ahmed', 'Fidan Samed Muhammed', 'Genc Kadriye', 'Geyik Hakan', 'Görkem Emre',
            'Gülle Halil Ibrahim', 'Gültekin Necdet', 'Gümus Tülay', 'Gün Cevat', 'Gürkan Kazim',
            'Güven, Ahmet', 'Güven, Hakki', 'Ibishi Elvir', 'Ince Kadir', 'Ince, Sahin',
            'Isik Ali', 'Kablan, Muharrem', 'Kahraman Hasan', 'Kahriman Cenap', 'Kahriman Nuren',
            'Kambir Kerim', 'Kambir, Yasar', 'Kandemir Bedriye', 'Kaplan Mustafa', 'Karalar, Ahmet',
            'Karasulu, Necmettin Ilker', 'Kardas, Murat', 'Kaya Mustafa', 'Kayatas Filiz', 'Kayatas, Ömer',
            'Keklik Sabri', 'Kökce Anil', 'Korkmaz, Cemal', 'Kovanci Özkan', 'Kovanci Sinan',
            'Krasnigi Nijazi', 'Külah Celal', 'Kurt Damla', 'Kurt Gökhan', 'Kurt Gül',
            'Kurt, Ahmet', 'Kutlu Aydin', 'Lackmann Günter', 'Mingir Murat', 'Özberk Ali',
            'Özberk, Emrah', 'Özdemir Erdal', 'Özdemir, Halim', 'Özdemir, Selim', 'Özdemir, Yusuf',
            'Özel, Halit', 'Özkul, Cahit', 'Özmen, Ercan', 'Ogultarhan Perigial', 'Orman Dilsiz Fatma',
            'Palali Ahmet Taha', 'Palali Hilmi', 'Palali, Arzu', 'Parlak Ismehan', 'Pesen, Münür',
            'Pinargil, Vefa', 'Pinargil-Moustafaoglou Gülay-Gkioulai', 'Polat Cengiz', 'Polat Sakir', 'Polat, Deniz',
            'Polat, Ergün', 'Rasimi, Resul', 'Saf Burcu Nur', 'Saf Eda', 'Saf Enes Süleyman',
            'Saf Hamza', 'Saf Ilhami', 'Saf Miray Tuana', 'Saf Seda', 'Saf Tahir',
            'Saf, Cengiz', 'Saf, Saadettin', 'Saglam, Abdullah', 'Salcan, Özkan', 'Sarac Furkan',
            'Sarac Riza-Yasin', 'Sari Oguzhan', 'Sentürklü Mertel', 'Sipahi, Resul', 'Sönmez, Hikmet',
            'Targan, Ersin', 'Tas Yalcin', 'Tasci, Sibel', 'Tastan Emre', 'Temiz, Murat',
            'Topcubasi, Özer', 'Türkan Hilal', 'Türkan Mesut', 'Ucar, Gökhan', 'Ünal Elyesa-Mübin',
            'Ünver Ahmet', 'Ünver Mustafa', 'Uyanik Cengiz', 'Uyanik, Fatih', 'Yaban, Serdar',
            'Yalmanci Erkan', 'Yavuz Halime', 'Yesilyurt Yusuf', 'Yilmaz Turgay', 'Yilmaz, Osman',
            'Yüregir Metin', 'Zandolu Ibrahim', 'Zorlu, Ertan'
        ];

        $this->info('📄 XML\'de ' . count($xmlNames) . ' üye var');
        $this->newLine();

        // Sistemde lastschrift_monthly olan üyeler
        $systemMembers = Member::where('payment_method', 'lastschrift_monthly')
            ->where('status', 'active')
            ->get();

        $this->info('💾 Sistemde ' . $systemMembers->count() . ' aylık Lastschrift üyesi var');
        $this->newLine();

        // Sistemde olan isimleri oluştur
        $systemNames = $systemMembers->map(function ($member) {
            return trim($member->name . ' ' . $member->surname);
        })->toArray();

        // Sistemde olup XML'de olmayan (FAZLA OLANLAR)
        $extraInSystem = [];
        foreach ($systemNames as $systemName) {
            $found = false;
            foreach ($xmlNames as $xmlName) {
                // Fuzzy match - isim benzerliği kontrolü
                if (
                    stripos($systemName, $xmlName) !== false ||
                    stripos($xmlName, $systemName) !== false ||
                    similar_text(strtolower($systemName), strtolower($xmlName), $percent) && $percent > 70
                ) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $extraInSystem[] = $systemName;
            }
        }

        // XML'de olup sistemde olmayan (EKSİK OLANLAR)
        $missingInSystem = [];
        foreach ($xmlNames as $xmlName) {
            $found = false;
            foreach ($systemNames as $systemName) {
                if (
                    stripos($systemName, $xmlName) !== false ||
                    stripos($xmlName, $systemName) !== false ||
                    similar_text(strtolower($systemName), strtolower($xmlName), $percent) && $percent > 70
                ) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $missingInSystem[] = $xmlName;
            }
        }

        // Sonuçları göster
        $this->newLine();
        $this->info('═══════════════════════════════════════════════════');
        $this->error('❌ SİSTEMDE FAZLA OLANLAR (XML\'de yok):');
        $this->info('═══════════════════════════════════════════════════');

        if (count($extraInSystem) > 0) {
            $this->table(['#', 'Ad Soyad'], array_map(function ($name, $index) {
                return [$index + 1, $name];
            }, $extraInSystem, array_keys($extraInSystem)));
            $this->error('📊 Toplam: ' . count($extraInSystem) . ' fazla üye');
        } else {
            $this->comment('   ✓ Sistemde fazla üye yok');
        }

        $this->newLine(2);
        $this->info('═══════════════════════════════════════════════════');
        $this->warn('⚠️  SİSTEMDE EKSİK OLANLAR (XML\'de var):');
        $this->info('═══════════════════════════════════════════════════');

        if (count($missingInSystem) > 0) {
            $this->table(['#', 'Ad Soyad'], array_map(function ($name, $index) {
                return [$index + 1, $name];
            }, $missingInSystem, array_keys($missingInSystem)));
            $this->warn('📊 Toplam: ' . count($missingInSystem) . ' eksik üye');
        } else {
            $this->comment('   ✓ Tüm XML üyeleri sistemde mevcut');
        }

        $this->newLine(2);
        $this->info('═══════════════════════════════════════════════════');
        $this->info('📊 ÖZET:');
        $this->info('═══════════════════════════════════════════════════');
        $this->info('   XML\'deki üye sayısı: ' . count($xmlNames));
        $this->info('   Sistemdeki aylık Lastschrift sayısı: ' . $systemMembers->count());
        $this->error('   Sistemde fazla olanlar: ' . count($extraInSystem));
        $this->warn('   Sistemde eksik olanlar: ' . count($missingInSystem));
        $this->newLine();

        return 0;
    }
}



