<?php

namespace App\Console\Commands;

use App\Models\Member;
use Illuminate\Console\Command;

class UpdateTemporaryEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:update-temp-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing temporary email addresses from @melle.de to @uye.com format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Geçici e-posta adresleri güncelleniyor...');

        // Find all members with @ditib.de emails
        $members = Member::where('email', 'like', '%@melle.de')->get();

        if ($members->isEmpty()) {
            $this->info('Güncellenecek geçici e-posta adresi bulunamadı.');
            return;
        }

        $this->info("Toplam {$members->count()} geçici e-posta adresi bulundu.");

        $updated = 0;
        $skipped = 0;

        foreach ($members as $member) {
            $oldEmail = $member->email;

            // Extract name and surname from the old email
            $emailParts = explode('@', $oldEmail);
            $localPart = $emailParts[0];

            // Create new email with @uye.com domain
            $newEmail = $localPart . '@uye.com';

            // Check if the new email already exists
            if (Member::where('email', $newEmail)->where('id', '!=', $member->id)->exists()) {
                $this->warn("E-posta çakışması: {$newEmail} zaten mevcut. Üye: {$member->name} {$member->surname}");
                $skipped++;
                continue;
            }

            // Update the email
            $member->update(['email' => $newEmail]);

            $this->line("✓ {$member->name} {$member->surname}: {$oldEmail} → {$newEmail}");
            $updated++;
        }

        $this->newLine();
        $this->info("Güncelleme tamamlandı!");
        $this->table(
            ['Durum', 'Sayı'],
            [
                ['Güncellenen', $updated],
                ['Atlanan', $skipped],
                ['Toplam', $members->count()]
            ]
        );
    }
}
