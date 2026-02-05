<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Member;

class AdminNewMemberNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $organizationName;

    /**
     * Create a new message instance.
     */
    public function __construct(Member $member, $organizationName)
    {
        $this->member = $member;
        $this->organizationName = $organizationName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('common.email_new_member_subject') . ' - ' . $this->organizationName,
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
            view: 'emails.admin-new-member-notification',
            with: [
                'member' => $this->member,
                'organizationName' => $this->organizationName,
            ],
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
