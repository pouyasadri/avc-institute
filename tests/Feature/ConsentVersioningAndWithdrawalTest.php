<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ConsentVersioningAndWithdrawalTest extends TestCase
{
    use RefreshDatabase;

    public function test_banner_hidden_when_version_matches()
    {
        Config::set('gdpr.privacy_policy_version', '1.0');

        $response = $this->withCookie('gdpr_consent', 'accepted:1.0')
            ->get('/en');

        $response->assertStatus(200);
        $response->assertDontSee('id="gdpr-cookie-banner"', false);
    }

    public function test_banner_shown_when_version_mismatch()
    {
        Config::set('gdpr.privacy_policy_version', '1.1');

        $response = $this->withCookie('gdpr_consent', 'accepted:1.0')
            ->get('/en');

        $response->assertStatus(200);
        $response->assertSee('id="gdpr-cookie-banner"', false);
        // Should also see policy updated notice
        $response->assertSee('Our privacy policy has been updated');
    }

    public function test_banner_shown_when_no_cookie()
    {
        Config::set('gdpr.privacy_policy_version', '1.0');

        $response = $this->get('/en');

        $response->assertStatus(200);
        $response->assertSee('id="gdpr-cookie-banner"', false);
    }

    public function test_consent_store_sets_versioned_cookie()
    {
        Config::set('gdpr.privacy_policy_version', '1.5');

        $response = $this->post('/gdpr/consent', ['consent' => 'accepted']);

        $response->assertCookie('gdpr_consent', 'accepted:1.5');
    }

    public function test_withdrawal_page_renders_in_all_locales()
    {
        foreach (['en', 'fr', 'fa'] as $locale) {
            $response = $this->get("/{$locale}/privacy/withdraw");
            $response->assertStatus(200);
        }
    }

    public function test_withdrawal_post_sets_withdrawn_cookie()
    {
        Config::set('gdpr.privacy_policy_version', '2.0');

        $response = $this->post('/en/privacy/withdraw');

        $response->assertCookie('gdpr_consent', 'withdrawn:2.0');
        $response->assertRedirect();
    }

    public function test_withdrawal_is_rate_limited()
    {
        // Hit the rate limit (5 attempts per minute)
        for ($i = 0; $i < 5; $i++) {
            $this->post('/en/privacy/withdraw');
        }

        $response = $this->post('/en/privacy/withdraw');
        $response->assertStatus(429);
    }

    public function test_gdpr_status_page_renders()
    {
        Config::set('gdpr.privacy_policy_version', '3.14');
        Config::set('gdpr.retention_days', 999);

        $response = $this->get('/en/privacy/status');

        $response->assertStatus(200);
        $response->assertSee('3.14');
        $response->assertSee('999 days');
    }
}
