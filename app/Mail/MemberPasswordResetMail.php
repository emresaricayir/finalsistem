<?php

namespace App\Mail;

use App\Models\Member;
use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $token;
    public $settings;

    /**
     * Create a new message instance.
     */
    public function __construct(Member $member, $token)
    {
        $this->member = $member;
        $this->token = $token;
        $this->settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'organization_address' => Settings::get('organization_address', ''),
            'organization_phone' => Settings::get('organization_phone', ''),
            'organization_email' => Settings::get('organization_email', 'info@camidernegi.com'),
            'logo' => Settings::get('logo'),
        ];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('common.email_password_reset_subject') . ' - ' . $this->settings['organization_name'],
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
            view: 'emails.member-password-reset',
            with: [
                'member' => $this->member,
                'token' => $this->token,
                'settings' => $this->settings,
                'resetUrl' => route('member.password.reset', [
                    'token' => $this->token,
                    'email' => $this->member->email
                ])
            ]
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
