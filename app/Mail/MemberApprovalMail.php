<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Member;

class MemberApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $organizationName;
    public $settings;

    /**
     * Create a new message instance.
     */
    public function __construct(Member $member, $organizationName, $settings = null)
    {
        $this->member = $member;
        $this->organizationName = $organizationName;
        $this->settings = $settings;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('common.email_member_approval_subject') . ' - ' . $this->organizationName,
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
            view: 'emails.member-approval',
            with: [
                'member' => $this->member,
                'organizationName' => $this->organizationName,
                'settings' => $this->settings,
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
