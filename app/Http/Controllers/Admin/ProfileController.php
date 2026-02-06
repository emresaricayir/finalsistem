<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('admin.profile.edit')->with('success', 'Profil bilgileri başarıyla güncellendi.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Enable 2FA
     */
    public function enableTwoFactor(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        if ($user->hasTwoFactorEnabled()) {
            return Redirect::route('admin.profile.edit')
                ->with('error', '2FA zaten aktif.');
        }

        $qrCodeUrl = $user->enableTwoFactorAuth();
        
        return Redirect::route('admin.profile.edit')
            ->with('two_factor_qr', $qrCodeUrl)
            ->with('two_factor_secret', $user->getTwoFactorSecret());
    }

    /**
     * Confirm 2FA setup
     */
    public function confirmTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        // Keep QR code and secret in session for retry
        $qrCode = session('two_factor_qr');
        $secret = session('two_factor_secret');

        if ($user->confirmTwoFactorAuth($request->code)) {
            return Redirect::route('admin.profile.edit')
                ->with('success', '2FA başarıyla aktif edildi.')
                ->with('recovery_codes', $user->getRecoveryCodes());
        }

        return Redirect::route('admin.profile.edit')
            ->with('error', 'Güvenlik kodu geçersiz. Lütfen tekrar deneyin.')
            ->with('two_factor_qr', $qrCode)
            ->with('two_factor_secret', $secret);
    }

    /**
     * Disable 2FA
     */
    public function disableTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $user->disableTwoFactorAuth();

        return Redirect::route('admin.profile.edit')
            ->with('success', '2FA başarıyla devre dışı bırakıldı.');
    }
}
