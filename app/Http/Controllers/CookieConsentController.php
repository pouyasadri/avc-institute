<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieConsentController extends Controller
{
    /**
     * Store the user's cookie-consent choice.
     *
     * Sets a 12-month first-party `gdpr_consent` cookie and stores the
     * matching flag in the session so that Microsoft Clarity is loaded
     * (or suppressed) on the very next page load.
     */
    public function store(Request $request): RedirectResponse
    {
        $accepted = $request->input('consent') === 'accepted';

        // 12-month cookie — HttpOnly, Secure, SameSite=Lax (set in config/session.php)
        $cookie = Cookie::make(
            name: 'gdpr_consent',
            value: ($accepted ? 'accepted' : 'rejected').':'.config('gdpr.privacy_policy_version'),
            minutes: 525_600, // 365 days × 24 h × 60 min
            path: '/',
            domain: null,
            secure: true,
            httpOnly: true,
            raw: false,
            sameSite: 'Lax',
        );

        // Drive the Clarity guard in main.blade.php via session flag
        session(['clarity_consent' => $accepted]);

        return redirect()->back()->withCookie($cookie);
    }
}
