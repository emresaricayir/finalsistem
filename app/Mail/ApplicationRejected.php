<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Member;

class ApplicationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $rejectionReason;

    /**
     * Create a new message instance.
     */
    public function __construct(Member $member, $rejectionReason)
    {
        $this->member = $member;
        $this->rejectionReason = $rejectionReason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('common.email_application_rejected_subject') . ' - ' . \App\Models\Settings::get('organization_name', 'Cami Üyelik'),
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
            view: 'emails.application-rejected',
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
