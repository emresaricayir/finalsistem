<?php

namespace App\Console\Commands;

use App\Models\Due;
use App\Models\Member;
use App\Mail\DueReminderMail;
use App\Services\EmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dues:send-reminders {--days=3 : Days before due date to send reminder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send due payment reminders to members';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysBeforeDue = $this->option('days');
        $reminderDate = Carbon::now()->addDays($daysBeforeDue);

        $this->info("Sending reminders for dues due on: " . $reminderDate->format('Y-m-d'));

        // Gecikmiş aidatlar için hatırlatma
        $overdueDues = Due::with('member')
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now())
            ->where('due_date', '>=', Carbon::now()->subDays(30)) // Son 30 gün
            ->get();

        $this->info("Found {$overdueDues->count()} overdue dues");

        foreach ($overdueDues as $due) {
            $this->sendReminder($due, true);
        }

        // Yaklaşan vade tarihleri için hatırlatma
        $upcomingDues = Due::with('member')
            ->where('status', 'pending')
            ->where('due_date', '=', $reminderDate->format('Y-m-d'))
            ->get();

        $this->info("Found {$upcomingDues->count()} upcoming dues");

        foreach ($upcomingDues as $due) {
            $this->sendReminder($due, false);
        }

        $this->info('Reminder emails sent successfully!');
    }

    private function sendReminder(Due $due, bool $isOverdue)
    {
        $member = $due->member;

        if (!$member->email) {
            $this->warn("No email for member: {$member->full_name}");
            return;
        }

        // Toplam gecikmiş borç hesapla
        $totalOverdue = $member->dues()
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', Carbon::now())
            ->sum('amount');

        try {
            // Use dynamic email template
            EmailService::sendDueReminder($member, $due, $totalOverdue);

            $this->info("Reminder sent to: {$member->full_name} ({$member->email})");

            // Aidat durumunu güncelle
            if ($isOverdue && $due->status !== 'overdue') {
                $due->update(['status' => 'overdue']);
            }

        } catch (\Exception $e) {
            $this->error("Failed to send reminder to {$member->email}: " . $e->getMessage());
        }
    }
}
