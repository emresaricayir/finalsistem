<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;

class UpdateMemberPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:update-passwords {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update member passwords based on birth dates (DD.MM.YYYY format)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }

        $members = Member::all();
        $updatedCount = 0;
        $skippedCount = 0;

        $this->info("📊 Processing {$members->count()} members...");

        foreach ($members as $member) {
            $newPassword = $this->generatePassword($member->birth_date);

            if ($isDryRun) {
                $this->line("👤 {$member->name} {$member->surname} ({$member->email})");
                $this->line("   📅 Birth Date: " . ($member->birth_date ? $member->birth_date->format('d.m.Y') : 'Not set'));
                $this->line("   🔑 New Password: {$newPassword}");
                $this->line("");
            } else {
                $member->update([
                    'password' => Hash::make($newPassword)
                ]);
                $this->line("✅ Updated password for {$member->name} {$member->surname}");
            }

            $updatedCount++;
        }

        if ($isDryRun) {
            $this->info("🔍 DRY RUN COMPLETE - {$updatedCount} members would be updated");
            $this->warn("Run without --dry-run to apply changes");
        } else {
            $this->info("✅ Password update complete! {$updatedCount} members updated");
        }
    }

    /**
     * Generate password based on birth date
     */
    private function generatePassword($birthDate)
    {
        if (!$birthDate) {
            return '123456'; // Default password if no birth date
        }

        // Convert birth date to DD.MM.YYYY format
        $date = \Carbon\Carbon::parse($birthDate);
        return $date->format('d.m.Y');
    }
}
