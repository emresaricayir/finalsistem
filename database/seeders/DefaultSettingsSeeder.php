<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings;

class DefaultSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'organization_name',
                'value' => 'Dernek Adı',
                'type' => 'text',
                'group' => 'organization',
                'label' => 'Organizasyon Adı',
                'description' => 'Cami derneğinin tam adı',
            ],
            [
                'key' => 'form_title',
                'value' => 'Üyelik Formu',
                'type' => 'text',
                'group' => 'organization',
                'label' => 'Form Başlığı',
                'description' => 'PDF form başlığı',
            ],
            [
                'key' => 'organization_phone',
                'value' => '0711 / 88 21 471',
                'type' => 'phone',
                'group' => 'contact',
                'label' => 'Telefon',
                'description' => 'Cami derneğinin telefon numarası',
            ],
            [
                'key' => 'organization_fax',
                'value' => '0711 / 88 21 472',
                'type' => 'phone',
                'group' => 'contact',
                'label' => 'Faks',
                'description' => 'Cami derneğinin faks numarası',
            ],
            [
                'key' => 'organization_email',
                'value' => 'emresaricayir@gmail.com',
                'type' => 'email',
                'group' => 'contact',
                'label' => 'E-posta',
                'description' => 'Cami derneğinin e-posta adresi',
            ],
            [
                'key' => 'organization_address',
                'value' => 'Musterstraße 123, 12345 Musterstadt',
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'Adres',
                'description' => 'Cami derneğinin adresi',
            ],
            [
                'key' => 'map_latitude',
                'value' => '52.2025',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Harita Enlemi (Latitude)',
                'description' => 'Google Maps için enlem koordinatı',
            ],
            [
                'key' => 'map_longitude',
                'value' => '8.2014',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Harita Boylamı (Longitude)',
                'description' => 'Google Maps için boylam koordinatı',
            ],
            [
                'key' => 'map_zoom',
                'value' => '15',
                'type' => 'number',
                'group' => 'contact',
                'label' => 'Harita Yakınlaştırma',
                'description' => 'Harita yakınlaştırma seviyesi (1-20)',
            ],
            [
                'key' => 'admin_username',
                'value' => 'admin',
                'type' => 'text',
                'group' => 'admin',
                'label' => 'Admin Kullanıcı Adı',
                'description' => 'Admin paneli kullanıcı adı',
            ],
            [
                'key' => 'admin_email',
                'value' => 'dernekmailiniz@dernek.com',
                'type' => 'email',
                'group' => 'admin',
                'label' => 'Admin E-posta',
                'description' => 'Admin e-posta adresi',
            ],
            [
                'key' => 'monthly_dues_default',
                'value' => '30.00',
                'type' => 'number',
                'group' => 'dues',
                'label' => 'Varsayılan Aylık Aidat',
                'description' => 'Yeni üyeler için varsayılan aylık aidat miktarı',
            ],
            [
                'key' => 'dues_generation_years',
                'value' => '5',
                'type' => 'number',
                'group' => 'dues',
                'label' => 'Aidat Oluşturma Yılı',
                'description' => 'Yeni üyeler için kaç yıllık aidat oluşturulacak',
            ],
            [
                'key' => 'overdue_days_threshold',
                'value' => '30',
                'type' => 'number',
                'group' => 'dues',
                'label' => 'Gecikmiş Aidat Eşiği (Gün)',
                'description' => 'Kaç gün sonra aidat gecikmiş sayılacak',
            ],
            [
                'key' => 'member_number_prefix',
                'value' => 'ÜYE',
                'type' => 'text',
                'group' => 'members',
                'label' => 'Üye Numarası Öneki',
                'description' => 'Üye numaralarının başlangıç harfleri',
            ],
            [
                'key' => 'auto_generate_member_numbers',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'members',
                'label' => 'Otomatik Üye Numarası',
                'description' => 'Yeni üyeler için otomatik numara oluşturulsun mu',
            ],
            [
                'key' => 'application_approval_required',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'applications',
                'label' => 'Başvuru Onayı Gerekli',
                'description' => 'Üyelik başvuruları için admin onayı gerekli mi',
            ],
            [
                'key' => 'pdf_font_size',
                'value' => '7',
                'type' => 'number',
                'group' => 'pdf',
                'label' => 'PDF Font Boyutu',
                'description' => 'PDF dosyalarında kullanılacak font boyutu',
            ],
            [
                'key' => 'pdf_single_page',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'pdf',
                'label' => 'Tek Sayfa PDF',
                'description' => 'PDF dosyaları tek sayfaya sığdırılsın mı',
            ],
            // Bank Information Settings
            [
                'key' => 'bank_name',
                'value' => 'Sparkasse Ludwigsburg',
                'type' => 'text',
                'group' => 'bank',
                'label' => 'Banka Adı',
                'description' => 'Derneğin hesabının bulunduğu banka adı',
            ],
            [
                'key' => 'account_holder',
                'value' => 'Dernek Adı e.V',
                'type' => 'text',
                'group' => 'bank',
                'label' => 'Hesap Sahibi',
                'description' => 'Banka hesabının sahibi',
            ],
            [
                'key' => 'bank_iban',
                'value' => 'DE12 6045 0050 0000 1234 56',
                'type' => 'text',
                'group' => 'bank',
                'label' => 'IBAN',
                'description' => 'Uluslararası banka hesap numarası',
            ],
            [
                'key' => 'bank_bic',
                'value' => 'SOLADES1LBG',
                'type' => 'text',
                'group' => 'bank',
                'label' => 'BIC/SWIFT',
                'description' => 'Banka tanımlayıcı kodu',
            ],
            [
                'key' => 'bank_purpose',
                'value' => 'Aidat Ödemesi',
                'type' => 'text',
                'group' => 'bank',
                'label' => 'Ödeme Açıklaması',
                'description' => 'Varsayılan ödeme açıklaması',
            ],
            // Social Media Settings
            [
                'key' => 'facebook_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Facebook sayfasının URL adresi',
            ],
            [
                'key' => 'instagram_url',
                'value' => '',
                'type' => 'url',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Instagram sayfasının URL adresi',
            ],
            // PDF Signature Names
            [
                'key' => 'pdf_member1_name',
                'value' => '',
                'type' => 'text',
                'group' => 'pdf',
                'label' => '1. Üye İmza Adı',
                'description' => 'PDF imza bölümünde 1. üye için gösterilecek isim',
            ],
            [
                'key' => 'pdf_member2_name',
                'value' => '',
                'type' => 'text',
                'group' => 'pdf',
                'label' => '2. Üye İmza Adı',
                'description' => 'PDF imza bölümünde 2. üye için gösterilecek isim',
            ],
            [
                'key' => 'pdf_president_name',
                'value' => '',
                'type' => 'text',
                'group' => 'pdf',
                'label' => 'Dernek Başkanı İmza Adı',
                'description' => 'PDF imza bölümünde dernek başkanı için gösterilecek isim',
            ],
        ];

        foreach ($settings as $setting) {
            Settings::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
