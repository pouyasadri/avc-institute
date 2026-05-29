<?php

namespace Tests\Feature;

use App\Models\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RedirectsTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_middleware_redirects_legacy_urls(): void
    {
        // Store redirect with path-only from_url (the new preferred format).
        // The middleware matches against path first, then fullUrl for legacy records.
        $fromPath = '/some-old-page';
        $toUrl = 'https://applyvipconseil.com/en/new-page';

        Redirect::create([
            'from_url'  => $fromPath,
            'to_url'    => $toUrl,
            'http_code' => 301,
        ]);

        $response = $this->get($fromPath);

        $response->assertRedirect($toUrl);
        $response->assertStatus(301);
    }

    public function test_redirects_middleware_caches_lookup(): void
    {
        // The middleware stores an array of scalars (not an Eloquent model).
        // Use `zeroOrMoreTimes()` so the assertion doesn't break when other
        // middleware (e.g. IP geolocation in LocaleDetector) also calls Cache::remember.
        Cache::shouldReceive('remember')
            ->zeroOrMoreTimes()
            ->andReturnUsing(function (string $key) {
                if (str_starts_with($key, 'redirect:')) {
                    return ['to' => 'https://new.com/new', 'code' => 302];
                }

                return null;
            });

        $response = $this->get('/old');

        $response->assertRedirect('https://new.com/new');
        $response->assertStatus(302);
    }

    public function test_no_redirect_if_not_found(): void
    {
        $response = $this->get('/en/regular-page');

        // Should not be redirected by our middleware; the page simply returns 404.
        $response->assertStatus(404);
    }
}
