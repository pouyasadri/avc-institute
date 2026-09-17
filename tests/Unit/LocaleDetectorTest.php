<?php

namespace Tests\Unit;

use App\Services\LocaleDetector;
use Illuminate\Http\Request;
use Tests\TestCase;

class LocaleDetectorTest extends TestCase
{
    /**
     * Test browser locale detection with quality values.
     */
    public function test_detects_locale_from_browser_accept_language()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'fr-FR,fr;q=0.9,en-US;q=0.8,en;q=0.7',
        ]);

        $detector = new LocaleDetector;
        $locale = $detector->detectFromBrowser($request);

        $this->assertEquals('fr', $locale);
    }

    /**
     * Test browser locale detection with Persian.
     */
    public function test_detects_persian_locale_from_browser()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'fa-IR,fa;q=0.9',
        ]);

        $detector = new LocaleDetector;
        $locale = $detector->detectFromBrowser($request);

        $this->assertEquals('fa', $locale);
    }

    /**
     * Test browser locale detection respects quality values.
     */
    public function test_respects_quality_values_in_accept_language()
    {
        // French has higher quality than English
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'en;q=0.5,fr;q=0.9',
        ]);

        $detector = new LocaleDetector;
        $locale = $detector->detectFromBrowser($request);

        $this->assertEquals('fr', $locale);
    }

    /**
     * Test browser locale detection with unsupported locale.
     */
    public function test_returns_null_for_unsupported_browser_locale()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'de-DE,de;q=0.9', // German not supported
        ]);

        $detector = new LocaleDetector;
        $locale = $detector->detectFromBrowser($request);

        $this->assertNull($locale);
    }

    /**
     * Test browser locale detection with no Accept-Language header.
     *
     * Note: In real scenarios without Accept-Language, detectFromBrowser should return null.
     * However, we test that the method handles missing/invalid headers gracefully.
     */
    public function test_returns_null_when_no_accept_language_header()
    {
        // Create a real request that explicitly has no HTTP_ACCEPT_LANGUAGE header
        $request = Request::create('/', 'GET');
        // Ensure the header is not set
        $request->server->remove('HTTP_ACCEPT_LANGUAGE');

        $detector = new LocaleDetector;
        $locale = $detector->detectFromBrowser($request);

        $this->assertNull($locale);
    }

    /**
     * Test locale detection priority with route parameter.
     */
    public function test_route_parameter_has_highest_priority()
    {
        $request = Request::create('/fr', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
        ]);

        session(['locale' => 'en']);

        $detector = new LocaleDetector;
        $locale = $detector->detectLocale($request, 'fr'); // Route locale is 'fr'

        $this->assertEquals('fr', $locale);
    }

    /**
     * Test locale detection with session taking priority over browser.
     */
    public function test_session_has_priority_over_browser()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9',
        ]);

        session(['locale' => 'fr']);

        $detector = new LocaleDetector;
        $locale = $detector->detectLocale($request);

        $this->assertEquals('fr', $locale);
    }

    /**
     * Test fallback to config when no detection method succeeds.
     */
    public function test_falls_back_to_config_locale()
    {
        config(['localization.fallback_locale' => 'en', 'app.locale' => 'en']);

        $request = Request::create('/', 'GET');

        $detector = new LocaleDetector;
        $locale = $detector->detectLocale($request);

        $this->assertEquals('en', $locale);
    }

    /**
     * Test detection info returns all sources.
     */
    public function test_get_detection_info_returns_all_sources()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_ACCEPT_LANGUAGE' => 'fr-FR,fr;q=0.9',
        ]);

        session(['locale' => 'fr']);

        $detector = new LocaleDetector;
        $info = $detector->getDetectionInfo($request);

        $this->assertArrayHasKey('detected_locale', $info);
        $this->assertArrayHasKey('browser_locale', $info);
        $this->assertArrayHasKey('session_locale', $info);
        $this->assertArrayHasKey('config_locale', $info);
        $this->assertArrayHasKey('accept_language', $info);
    }

    /**
     * Test local IP detection is skipped.
     */
    public function test_skips_ip_detection_for_local_addresses()
    {
        $request = Request::create('/', 'GET');
        $request->server->set('REMOTE_ADDR', '127.0.0.1');

        $detector = new LocaleDetector;
        $locale = $detector->detectFromIp($request);

        $this->assertNull($locale);
    }
}
