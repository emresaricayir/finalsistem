<?php

namespace App\Mail;

use App\Models\Member;
use App\Models\Due;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DueReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $due;
    public $totalOverdue;

    /**
     * Create a new message instance.
     */
    public function __construct(Member $member, Due $due, $totalOverdue = 0)
    {
        $this->member = $member;
        $this->due = $due;
        $this->totalOverdue = $totalOverdue;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('common.email_due_reminder_subject') . ' - ' . $this->member->full_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Set locale for email (default to Turkish)
        app()->setLocale('tr');
        
        return new Content(
            view: 'emails.due-reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
