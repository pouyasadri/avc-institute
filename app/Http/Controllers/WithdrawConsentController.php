<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class WithdrawConsentController extends Controller
{
    public function show()
    {
        return view('pages.withdraw-consent');
    }

    public function withdraw(Request $request)
    {
        // Set the cookie to withdrawn:version
        $version = config('gdpr.privacy_policy_version');
        $cookie = Cookie::make(
            name: 'gdpr_consent',
            value: 'withdrawn:'.$version,
            minutes: 525_600, // 365 days
            path: '/',
            domain: null,
            secure: true,
            httpOnly: true,
            raw: false,
            sameSite: 'Lax',
        );

        // Clear the clarity session flag
        session(['clarity_consent' => false]);

        return redirect()->back()
            ->withCookie($cookie)
            ->with('success', __('privacy.withdraw.success'));
    }
}
