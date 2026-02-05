<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\MemberPasswordResetMail;

class MemberPasswordResetController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'organization_address' => Settings::get('organization_address', ''),
            'organization_phone' => Settings::get('organization_phone', ''),
            'organization_fax' => Settings::get('organization_fax', ''),
            'organization_email' => Settings::get('organization_email', 'info@camidernegi.com'),
            'logo' => Settings::get('logo'),
        ];

        return view('member.auth.forgot-password', compact('settings'));
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:members,email',
        ], [
            'email.required' => __('common.validation_email_required'),
            'email.email' => __('common.validation_email_email'),
            'email.exists' => __('common.validation_email_exists'),
        ]);

        $member = Member::where('email', $request->email)
                       ->where('status', 'active')
                       ->where('application_status', 'approved')
                       ->first();

        if (!$member) {
            return back()->withErrors([
                'email' => __('common.validation_email_not_found')
            ]);
        }

        // Generate token
        $token = Str::random(64);

        // Store token in database
        DB::table('member_password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Send email
        try {
            Mail::to($member->email)->send(new MemberPasswordResetMail($member, $token));

            return back()->with('success', __('common.password_reset_success'));
        } catch (\Exception $e) {
            \Log::error('Member password reset email error: ' . $e->getMessage());
            return back()->withErrors([
                'email' => __('common.validation_email_send_error')
            ]);
        }
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        // Verify token
        $passwordReset = DB::table('member_password_resets')
            ->where('email', $email)
            ->first();

        if (!$passwordReset || !Hash::check($token, $passwordReset->token)) {
            return redirect()->route('member.forgot-password')
                ->withErrors(['email' => __('common.validation_token_invalid')]);
        }

        // Check if token is expired (24 hours)
        if (now()->diffInHours($passwordReset->created_at) > 24) {
            DB::table('member_password_resets')->where('email', $email)->delete();
            return redirect()->route('member.forgot-password')
                ->withErrors(['email' => __('common.validation_token_expired')]);
        }

        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'logo' => Settings::get('logo'),
        ];

        return view('member.auth.reset-password', compact('settings', 'token', 'email'));
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => __('common.validation_password_required'),
            'password.min' => __('common.validation_password_min'),
            'password.confirmed' => __('common.validation_password_confirmed'),
        ]);

        // Verify token
        $passwordReset = DB::table('member_password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['token' => __('common.validation_token_invalid')]);
        }

        // Check if token is expired (24 hours)
        if (now()->diffInHours($passwordReset->created_at) > 24) {
            DB::table('member_password_resets')->where('email', $request->email)->delete();
            return redirect()->route('member.forgot-password')
                ->withErrors(['email' => __('common.validation_token_expired')]);
        }

        // Update member password
        $member = Member::where('email', $request->email)->first();
        if ($member) {
            $member->update([
                'password' => Hash::make($request->password)
            ]);

            // Delete token
            DB::table('member_password_resets')->where('email', $request->email)->delete();

            return redirect()->route('member.login')
                ->with('success', __('common.password_update_success'));
        }

        return back()->withErrors(['email' => __('common.validation_member_not_found')]);
    }
}
