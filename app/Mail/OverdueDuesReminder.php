<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Member;

class OverdueDuesReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $overdueAmount;
    public $overdueMonths;

    /**
     * Create a new message instance.
     */
    public function __construct(Member $member, $overdueAmount, $overdueMonths)
    {
        $this->member = $member;
        $this->overdueAmount = $overdueAmount;
        $this->overdueMonths = $overdueMonths;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('common.email_overdue_reminder_subject') . ' - ' . \App\Models\Settings::get('organization_name', 'Cami Üyelik'),
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
            view: 'emails.overdue-dues-reminder',
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
