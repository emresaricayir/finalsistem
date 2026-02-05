<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberPasswordSetupController extends Controller
{
    /**
     * Show password setup form
     */
    public function showSetupForm(Request $request)
    {
        $token = $request->token;

        if (!$token) {
            return redirect()->route('member.login')
                ->withErrors(['token' => 'Geçersiz şifre belirleme bağlantısı.']);
        }

        // Find member by activation token
        $member = Member::where('activation_token', $token)->first();

        if (!$member) {
            return redirect()->route('member.login')
                ->withErrors(['token' => 'Geçersiz veya süresi dolmuş şifre belirleme bağlantısı.']);
        }

        $settings = [
            'organization_name' => Settings::get('organization_name', 'Cami Derneği'),
            'logo' => Settings::get('logo'),
        ];

        return view('member.auth.password-setup', compact('settings', 'token', 'member'));
    }

    /**
     * Setup password
     */
    public function setupPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'Şifre gereklidir.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            'password.confirmed' => 'Şifre onayı eşleşmiyor.',
        ]);

        // Find member by activation token
        $member = Member::where('activation_token', $request->token)->first();

        if (!$member) {
            return back()->withErrors(['token' => 'Geçersiz şifre belirleme bağlantısı.']);
        }

        // Update member password and clear activation token
        $member->update([
            'password' => Hash::make($request->password),
            'activation_token' => null
        ]);

        return redirect()->route('member.login')
            ->with('success', 'Şifreniz başarıyla belirlendi. Yeni şifrenizle giriş yapabilirsiniz.');
    }
}
