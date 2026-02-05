<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\EmailLog;
use App\Models\Settings;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class EmailService
{
    /**
     * Send email using template
     */
    public static function sendTemplate($templateKey, $recipientEmail, $variables = [], $options = [])
    {
        $template = EmailTemplate::getByKey($templateKey);
        $batchId = $options['batch_id'] ?? null;
        $sentBy = $options['sent_by'] ?? null;
        $recipientName = $options['recipient_name'] ?? null;

        // Create email log entry
        $emailLog = EmailLog::create([
            'template_key' => $templateKey,
            'template_name' => $template ? $template->name : 'Bilinmeyen Şablon',
            'recipient_email' => $recipientEmail,
            'recipient_name' => $recipientName,
            'subject' => $template ? $template->subject : 'Konu Yok',
            'status' => 'pending',
            'variables' => $variables,
            'sent_by' => $sentBy,
            'batch_id' => $batchId,
        ]);

        try {
            if (!$template) {
                $emailLog->update([
                    'status' => 'failed',
                    'error_message' => "Email template not found: {$templateKey}",
                ]);
                Log::error("Email template not found: {$templateKey}");
                return false;
            }

            // Add default variables
            $defaultVariables = [
                'organization_name' => Settings::get('organization_name', 'Dernek Adı'),
                'settings' => [
                    'organization_name' => Settings::get('organization_name', 'Dernek Adı'),
                    'organization_address' => Settings::get('organization_address', ''),
                    'organization_phone' => Settings::get('organization_phone', ''),
                    'organization_email' => Settings::get('organization_email', ''),
                ],
            ];

            $allVariables = array_merge($defaultVariables, $variables);

            // Render template
            $rendered = $template->render($allVariables);

            // Update log with rendered subject
            $emailLog->update(['subject' => $rendered['subject']]);

            // Send email
            Mail::html($rendered['html_content'], function ($message) use ($recipientEmail, $rendered) {
                $message->to($recipientEmail)
                        ->subject($rendered['subject']);
            });

            // Mark as sent
            $emailLog->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);


            Log::info("Email sent successfully", [
                'template' => $templateKey,
                'recipient' => $recipientEmail,
                'subject' => $rendered['subject']
            ]);

            return true;

        } catch (\Exception $e) {
            // Mark as failed
            $emailLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error("Email sending failed", [
                'template' => $templateKey,
                'recipient' => $recipientEmail,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send member welcome email
     */
    public static function sendMemberWelcome($member, $options = [])
    {
        $variables = [
            'member' => $member,
        ];

        return self::sendTemplate('member-welcome', $member->email, $variables, $options);
    }

    /**
     * Send due reminder email
     */
    public static function sendDueReminder($member, $due, $totalOverdue = 0, $options = [])
    {
        $variables = [
            'member' => $member,
            'due' => $due,
            'totalOverdue' => $totalOverdue,
        ];

        return self::sendTemplate('due-reminder', $member->email, $variables, $options);
    }

    /**
     * Send member approval email
     */
    public static function sendMemberApproval($member, $options = [])
    {
        try {
            $organizationName = Settings::get('organization_name', 'Cami Derneği');

            // Use settings from options if provided, otherwise get from database
            $settings = $options['settings'] ?? [
                'organization_name' => $organizationName,
                'organization_email' => Settings::get('organization_email', ''),
                'organization_phone' => Settings::get('organization_phone', ''),
                'organization_address' => Settings::get('organization_address', ''),
                'bank_name' => Settings::get('bank_name', ''),
                'account_holder' => Settings::get('account_holder', ''),
                'bank_iban' => Settings::get('bank_iban', ''),
                'bank_bic' => Settings::get('bank_bic', ''),
                'bank_purpose' => Settings::get('bank_purpose', 'Aidat Ödemesi'),
            ];

            // Determine password display logic based on member creation method
            $passwordInfo = self::getPasswordInfo($member);

            // Try template first
            $template = EmailTemplate::getByKey('member-approval');

            Log::info("Email template lookup", [
                'template_key' => 'member-approval',
                'template_found' => $template ? true : false,
                'template_id' => $template ? $template->id : null,
                'template_active' => $template ? $template->is_active : null
            ]);

            if ($template) {
                $variables = [
                    'member' => $member,
                    'organizationName' => $organizationName,
                    'settings' => $settings,
                    'passwordInfo' => $passwordInfo,
                    'loginUrl' => route('member.login'),
                ];

                Log::info("Attempting to send member approval email via template", [
                    'member_id' => $member->id,
                    'member_email' => $member->email,
                    'template_found' => true,
                    'settings_count' => count($settings)
                ]);

                return self::sendTemplate('member-approval', $member->email, $variables, $options);
            }

            // Fallback: Send direct mail using blade template
            Log::info("Template not found, using fallback blade template", [
                'member_id' => $member->id,
                'member_email' => $member->email,
            ]);

            // Create email log entry for fallback
            $sentBy = $options['sent_by'] ?? null;
            $recipientName = $options['recipient_name'] ?? ($member->name . ' ' . $member->surname);
            $subject = 'Üyeliğiniz Onaylandı - ' . $organizationName;

            $emailLog = EmailLog::create([
                'template_key' => 'member-approval',
                'template_name' => 'Üyelik Onay E-postası (Fallback)',
                'recipient_email' => $member->email,
                'recipient_name' => $recipientName,
                'subject' => $subject,
                'status' => 'pending',
                'variables' => ['member' => $member, 'settings' => $settings],
                'sent_by' => $sentBy,
                'batch_id' => $options['batch_id'] ?? null,
            ]);

            try {
                $loginUrl = route('member.login');
                Mail::send('emails.member-approval', compact('member', 'organizationName', 'settings', 'passwordInfo', 'loginUrl'), function ($message) use ($member, $organizationName, $subject) {
                    $message->to($member->email, $member->name . ' ' . $member->surname)
                            ->subject($subject)
                            ->from(config('mail.from.address'), $organizationName);
                });

                // Mark as sent
                $emailLog->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                Log::info("Member approval email sent directly via blade template to: {$member->email}");
                return true;
            } catch (\Exception $e) {
                // Mark as failed
                $emailLog->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                throw $e; // Re-throw to be caught by outer catch
            }

        } catch (\Exception $e) {
            Log::error("Failed to send member approval email", [
                'member_id' => $member->id,
                'member_email' => $member->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Determine password information based on member creation method
     */
    private static function getPasswordInfo($member)
    {
        // Check if member has activation_token (admin panel creation)
        if ($member->activation_token) {
            return [
                'type' => 'admin_created',
                'showPassword' => false,
                'showPasswordSetupLink' => true,
                'passwordSetupUrl' => url('/sifre-olustur?token=' . $member->activation_token),
            ];
        }

        // Check if password is birth date format (Excel import)
        $birthDatePassword = $member->birth_date ? $member->birth_date->format('dmY') : null;
        if ($birthDatePassword && Hash::check($birthDatePassword, $member->password)) {
            return [
                'type' => 'excel_import',
                'showPassword' => true,
                'password' => $birthDatePassword,
                'showPasswordSetupLink' => false,
            ];
        }

        // Check if password is default 123456 (Excel import without birth date)
        if (Hash::check('123456', $member->password)) {
            return [
                'type' => 'excel_import_default',
                'showPassword' => true,
                'password' => '123456',
                'showPasswordSetupLink' => false,
            ];
        }

        // Online application - user created their own password
        return [
            'type' => 'online_application',
            'showPassword' => false,
            'showPasswordSetupLink' => false,
        ];
    }

    /**
     * Send application confirmation email
     */
    public static function sendApplicationConfirmation($member, $options = [])
    {
        $variables = [
            'member' => $member,
        ];

        return self::sendTemplate('application-confirmation', $member->email, $variables, $options);
    }

    /**
     * Send application rejected email
     */
    public static function sendApplicationRejected($member, $options = [])
    {
        $variables = [
            'member' => $member,
        ];

        return self::sendTemplate('application-rejected', $member->email, $variables, $options);
    }

    /**
     * Send overdue dues reminder email
     */
    public static function sendOverdueDuesReminder($member, $overdueDues, $options = [])
    {
        try {
            // Try template first
            $template = EmailTemplate::getByKey('overdue-dues-reminder');

            if ($template) {
                $variables = [
                    'member' => $member,
                    'overdueDues' => $overdueDues,
                ];

                return self::sendTemplate('overdue-dues-reminder', $member->email, $variables, $options);
            }

            // Fallback: Send direct mail using blade template
            Log::info("Overdue dues reminder template not found, using fallback blade template", [
                'member_id' => $member->id,
                'member_email' => $member->email,
            ]);

            // Create email log entry for fallback
            $sentBy = $options['sent_by'] ?? null;
            $recipientName = $options['recipient_name'] ?? ($member->name . ' ' . $member->surname);
            $organizationName = Settings::get('organization_name', 'Cami Derneği');
            $subject = 'Aidat Ödeme Hatırlatması - ' . $organizationName;

            $emailLog = EmailLog::create([
                'template_key' => 'overdue-dues-reminder',
                'template_name' => 'Gecikmiş Aidat Hatırlatması (Fallback)',
                'recipient_email' => $member->email,
                'recipient_name' => $recipientName,
                'subject' => $subject,
                'status' => 'pending',
                'variables' => ['member' => $member, 'overdueDues' => $overdueDues],
                'sent_by' => $sentBy,
                'batch_id' => $options['batch_id'] ?? null,
            ]);

            try {
                Mail::send('emails.overdue-dues-reminder', compact('member', 'overdueDues', 'organizationName'), function ($message) use ($member, $organizationName, $subject) {
                    $message->to($member->email, $member->name . ' ' . $member->surname)
                            ->subject($subject)
                            ->from(config('mail.from.address'), $organizationName);
                });

                // Mark as sent
                $emailLog->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                Log::info("Overdue dues reminder email sent directly via blade template to: {$member->email}");
                return true;
            } catch (\Exception $e) {
                // Mark as failed
                $emailLog->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                throw $e; // Re-throw to be caught by outer catch
            }

        } catch (\Exception $e) {
            Log::error("Failed to send overdue dues reminder email", [
                'member_id' => $member->id,
                'member_email' => $member->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send admin new member notification email
     */
    public static function sendAdminNewMemberNotification($member, $adminEmail)
    {
        $variables = [
            'member' => $member,
        ];

        return self::sendTemplate('admin-new-member-notification', $adminEmail, $variables);
    }

    /**
     * Send password reset email
     */
    public static function sendPasswordReset($member, $resetUrl)
    {
        $variables = [
            'member' => $member,
            'resetUrl' => $resetUrl,
        ];

        return self::sendTemplate('password-reset', $member->email, $variables);
    }
}
