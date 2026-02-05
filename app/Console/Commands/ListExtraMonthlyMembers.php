<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListExtraMonthlyMembers extends Command
{
    protected $signature = 'members:list-extra-monthly';
    protected $description = 'List members who are in system with monthly Lastschrift but not in XML';

    public function handle()
    {
        $this->info('📋 Sistemde Fazla Olan Aylık Lastschrift Üyeleri');
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

        // XML isimlerini normalize et ve her iki sırayı da kaydet
        $xmlNormalized = [];
        foreach ($xmlNames as $name) {
            $clean = $this->normalizeName($name);
            $xmlNormalized[] = $clean;

            // Eğer virgül varsa veya boşluk varsa, ters çevir
            $parts = preg_split('/[,\s]+/', $clean);
            if (count($parts) >= 2) {
                // Tersini de ekle (örn: "Ahmet Kurt" -> "Kurt Ahmet")
                $reversed = $parts[count($parts) - 1] . ' ' . implode(' ', array_slice($parts, 0, -1));
                $xmlNormalized[] = $reversed;
            }
        }
        $xmlNormalized = array_unique($xmlNormalized);

        $this->info('📄 XML\'de ' . count($xmlNames) . ' üye var (normalize edilmiş: ' . count($xmlNormalized) . ')');

        // Sistemde lastschrift_monthly olan üyeler
        $systemMembers = Member::where('payment_method', 'lastschrift_monthly')
            ->where('status', 'active')
            ->orderBy('surname')
            ->orderBy('name')
            ->get();

        $this->info('💾 Sistemde ' . $systemMembers->count() . ' aylık Lastschrift üyesi var');
        $this->newLine();

        // Sistemde olup XML'de olmayan
        $extraMembers = [];
        foreach ($systemMembers as $member) {
            $fullName = $this->normalizeName($member->name . ' ' . $member->surname);
            $reverseName = $this->normalizeName($member->surname . ' ' . $member->name);

            // Hem düz hem ters ismi kontrol et
            $foundInXml = false;
            foreach ($xmlNormalized as $xmlName) {
                if ($this->namesMatch($fullName, $xmlName) || $this->namesMatch($reverseName, $xmlName)) {
                    $foundInXml = true;
                    break;
                }
            }

            if (!$foundInXml) {
                $extraMembers[] = [
                    'id' => $member->id,
                    'member_number' => $member->member_number ?? '-',
                    'name' => $member->name,
                    'surname' => $member->surname,
                    'monthly_dues' => $member->monthly_dues ?? 0,
                    'membership_date' => $member->membership_date ? $member->membership_date->format('d.m.Y') : '-',
                ];
            }
        }

        // Sonuçları göster
        $this->newLine();
        $this->info('═══════════════════════════════════════════════════════════════');
        $this->error('❌ SİSTEMDE FAZLA OLANLAR (XML\'de yok - Aylık Lastschrift):');
        $this->info('═══════════════════════════════════════════════════════════════');

        if (count($extraMembers) > 0) {
            $this->table(
                ['#', 'ID', 'Üye No', 'Ad', 'Soyad', 'Aylık Aidat', 'Üyelik Tarihi'],
                array_map(function ($member, $index) {
                    return [
                        $index + 1,
                        $member['id'],
                        $member['member_number'],
                        $member['name'],
                        $member['surname'],
                        number_format($member['monthly_dues'], 2) . ' €',
                        $member['membership_date'],
                    ];
                }, $extraMembers, array_keys($extraMembers))
            );
            $this->newLine();
            $this->error('📊 Toplam: ' . count($extraMembers) . ' fazla üye');
            $this->newLine();
            $this->warn('💡 Bu üyeler XML\'de bulunmuyor. Şunlar olabilir:');
            $this->warn('   - Aylık Lastschrift\'ten vazgeçmişler');
            $this->warn('   - Yıllık veya 6 aylığa geçmişler');
            $this->warn('   - Bankadan manuel ödemeye geçmişler');
            $this->warn('   - Üyelikleri pasif olmuş ama sistemde aktif görünüyor');
        } else {
            $this->comment('   ✓ Sistemde fazla üye yok - XML ile tam uyumlu!');
        }

        $this->newLine();

        return 0;
    }

    /**
     * İsmi normalize et (küçük harf, Türkçe karakterler düzelt, fazla boşluk temizle)
     */
    private function normalizeName($name)
    {
        // Küçük harfe çevir
        $name = mb_strtolower($name, 'UTF-8');

        // Türkçe karakterleri normalize et
        $name = str_replace(
            ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç'],
            ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c'],
            $name
        );

        // Virgül ve noktaları temizle
        $name = str_replace([',', '.', '-', '/'], ' ', $name);

        // Fazla boşlukları temizle
        $name = preg_replace('/\s+/', ' ', trim($name));

        return $name;
    }

    /**
     * İki ismin aynı olup olmadığını kontrol et
     */
    private function namesMatch($name1, $name2)
    {
        // Exact match
        if ($name1 === $name2) {
            return true;
        }

        // Benzerlik oranı kontrolü
        similar_text($name1, $name2, $percent);
        if ($percent > 90) {
            return true;
        }

        // Bir isim diğerinin içinde mi?
        if (strlen($name1) > 5 && strlen($name2) > 5) {
            if (strpos($name1, $name2) !== false || strpos($name2, $name1) !== false) {
                return true;
            }
        }

        return false;
    }
}



