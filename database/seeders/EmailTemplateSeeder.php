<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'key' => 'member-welcome',
                'name' => 'Üye Hoş Geldin E-postası',
                'subject' => 'Hoş Geldiniz {{ $member->name }} - {{ $organization_name }}',
                'description' => 'Yeni üyeler için hoş geldin e-postası',
                'variables' => ['member', 'organization_name', 'settings'],
                'html_content' => file_get_contents(resource_path('views/emails/member-welcome.blade.php')),
                'is_active' => true,
            ],
            [
                'key' => 'due-reminder',
                'name' => 'Aidat Hatırlatma E-postası',
                'subject' => 'Aidat Ödeme Hatırlatması - {{ $member->name }} ({{ $organization_name }})',
                'description' => 'Aidat ödemesi için hatırlatma e-postası',
                'variables' => ['member', 'due', 'totalOverdue', 'organization_name'],
                'html_content' => file_get_contents(resource_path('views/emails/due-reminder.blade.php')),
                'is_active' => true,
            ],
            [
                'key' => 'member-approval',
                'name' => 'Üyelik Onay E-postası',
                'subject' => 'Üyeliğiniz Onaylandı {{ $member->name }} - {{ $organizationName }}',
                'description' => 'Üyelik başvurusu onaylandığında gönderilen e-posta',
                'variables' => ['member', 'organizationName', 'settings'],
                'html_content' => file_get_contents(resource_path('views/emails/member-approval-template.html')),
                'is_active' => true,
            ],
            [
                'key' => 'application-confirmation',
                'name' => 'Başvuru Onay E-postası',
                'subject' => 'Başvurunuz Alındı {{ $member->name }} - {{ $organization_name }}',
                'description' => 'Üyelik başvurusu alındığında gönderilen e-posta',
                'variables' => ['member', 'organization_name', 'settings'],
                'html_content' => file_get_contents(resource_path('views/emails/application-confirmation.blade.php')),
                'is_active' => true,
            ],
            [
                'key' => 'application-rejected',
                'name' => 'Başvuru Red E-postası',
                'subject' => 'Başvuru Durumu {{ $member->name }} - {{ $organization_name }}',
                'description' => 'Üyelik başvurusu reddedildiğinde gönderilen e-posta',
                'variables' => ['member', 'organization_name', 'settings'],
                'html_content' => file_get_contents(resource_path('views/emails/application-rejected.blade.php')),
                'is_active' => true,
            ],
            [
                'key' => 'overdue-dues-reminder',
                'name' => 'Gecikmiş Aidat Hatırlatma E-postası',
                'subject' => 'Gecikmiş Aidat Hatırlatması - {{ $member->name }} ({{ $organization_name }})',
                'description' => 'Gecikmiş aidat ödemeleri için hatırlatma e-postası',
                'variables' => ['member', 'overdueDues', 'organization_name'],
                'html_content' => file_get_contents(resource_path('views/emails/overdue-dues-reminder.blade.php')),
                'is_active' => false, // Devre dışı bırak, fallback kullan
            ],
            [
                'key' => 'admin-new-member-notification',
                'name' => 'Yeni Üye Bildirim E-postası (Admin)',
                'subject' => 'Yeni Üye Başvurusu: {{ $member->name }} - {{ $organization_name }}',
                'description' => 'Yeni üye başvurusu olduğunda adminlere gönderilen e-posta',
                'variables' => ['member', 'organization_name', 'settings'],
                'html_content' => file_get_contents(resource_path('views/emails/admin-new-member-notification.blade.php')),
                'is_active' => true,
            ],
            [
                'key' => 'password-reset',
                'name' => 'Şifre Sıfırlama E-postası',
                'subject' => 'Şifre Sıfırlama - {{ $member->name }} ({{ $organization_name }})',
                'description' => 'Şifre sıfırlama için gönderilen e-posta',
                'variables' => ['member', 'resetUrl', 'organization_name'],
                'html_content' => file_get_contents(resource_path('views/emails/password-reset.blade.php')),
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }
    }
}
