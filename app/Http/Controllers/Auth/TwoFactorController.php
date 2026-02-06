<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TwoFactorController extends Controller
{
    /**
     * Show 2FA verification form
     */
    public function show(): View
    {
        if (!session('login.id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor');
    }

    /**
     * Verify 2FA code
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $userId = session('login.id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        // Check recovery code first
        if ($user->useRecoveryCode($request->code)) {
            Auth::login($user, session('login.remember', false));
            $request->session()->forget(['login.id', 'login.remember']);
            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        // Verify 2FA code
        if ($user->verifyTwoFactorCode($request->code)) {
            Auth::login($user, session('login.remember', false));
            $request->session()->forget(['login.id', 'login.remember']);
            $request->session()->regenerate();
            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'code' => 'Güvenlik kodu geçersiz.',
        ]);
    }
}
