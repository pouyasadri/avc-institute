<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LyonClusterGeoSeoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Lyon 2 university page renders GEO capsule, portal info, tuition, comparison and FAQ schema.
     */
    public function test_lyon_2_renders_geo_capsule_tuition_and_faq_schema(): void
    {
        $response = $this->get('/fa/universities/lyon-2');

        $response->assertStatus(200);

        // Assert GEO Answer Capsule is present
        $response->assertSee('خلاصه راهنمای پذیرش دانشگاه لیون ۲', false);
        $response->assertSee('دانشگاه دولتی لومیر لیون ۲', false);
        $response->assertSee('Campus France و Mon Master', false);

        // Assert Portal guidance & website
        $response->assertSee('سایت دانشگاه لیون ۲ و پورتال‌های ثبت‌نام', false);
        $response->assertSee('univ-lyon2.fr', false);

        // Assert Tuition & Exemption
        $response->assertSee('شهریه دانشگاه لیون ۲ و شرایط معافیت برای دانشجویان ایرانی', false);
        $response->assertSee('Exonération partielle', false);

        // Assert Comparison Silo links to Lyon 1, Lyon 3, and Lyon City
        $response->assertSee('/fa/universities/lyon-1', false);
        $response->assertSee('/fa/universities/lyon-3', false);
        $response->assertSee('/fa/cities/lyon', false);

        // Assert Schema.org FAQPage is emitted in JSON-LD
        $response->assertSee('"@type": "FAQPage"', false);
        $response->assertSee('آیا دانشگاه لیون ۲ بدون مدرک زبان فرانسه پذیرش می‌دهد؟', false);

        // Assert no untranslated keys
        $response->assertDontSee('university/lyon-2.');
    }

    /**
     * Test Lyon 1 university page renders GEO capsule, tuition, and FAQ schema.
     */
    public function test_lyon_1_renders_geo_capsule_tuition_and_faq_schema(): void
    {
        $response = $this->get('/fa/universities/lyon-1');

        $response->assertStatus(200);

        // Assert GEO Answer Capsule is present
        $response->assertSee('خلاصه راهنمای پذیرش دانشگاه لیون ۱', false);
        $response->assertSee('کلود برنارد لیون ۱', false);
        $response->assertSee('پردیس اصلی لا دوآ', false);

        // Assert Tuition & Exemption
        $response->assertSee('شهریه دانشگاه لیون ۱ و شرایط معافیت برای ایرانیان', false);

        // Assert Comparison Silo links
        $response->assertSee('/fa/universities/lyon-2', false);
        $response->assertSee('/fa/universities/lyon-3', false);
        $response->assertSee('/fa/cities/lyon', false);

        // Assert Schema.org FAQPage
        $response->assertSee('"@type": "FAQPage"', false);
        $response->assertSee('آیا دانشگاه لیون ۱ در رشته پزشکی و داروسازی معتبر است؟', false);

        // Assert no untranslated keys
        $response->assertDontSee('university/lyon-1.');
    }

    /**
     * Test Lyon 3 university page renders GEO capsule, tuition, and FAQ schema.
     */
    public function test_lyon_3_renders_geo_capsule_tuition_and_faq_schema(): void
    {
        $response = $this->get('/fa/universities/lyon-3');

        $response->assertStatus(200);

        // Assert GEO Answer Capsule is present
        $response->assertSee('خلاصه راهنمای پذیرش دانشگاه لیون ۳', false);
        $response->assertSee('ژان مولن لیون ۳', false);
        $response->assertSee('IAE Lyon', false);

        // Assert Tuition & Exemption
        $response->assertSee('شهریه دانشگاه لیون ۳ و مدرسه مدیریت IAE', false);

        // Assert Comparison Silo links
        $response->assertSee('/fa/universities/lyon-1', false);
        $response->assertSee('/fa/universities/lyon-2', false);
        $response->assertSee('/fa/cities/lyon', false);

        // Assert Schema.org FAQPage
        $response->assertSee('"@type": "FAQPage"', false);
        $response->assertSee('آیا مدرسه مدیریت IAE لیون معتبر است و رشته انگلیسی دارد؟', false);

        // Assert no untranslated keys
        $response->assertDontSee('university/lyon-3.');
    }

    /**
     * Test City of Lyon guide renders university cluster cards and FAQ schema.
     */
    public function test_city_lyon_renders_cluster_cards_and_faq_schema(): void
    {
        $response = $this->get('/fa/cities/lyon');

        $response->assertStatus(200);

        // Assert university cards with links
        $response->assertSee('/fa/universities/lyon-1', false);
        $response->assertSee('/fa/universities/lyon-2', false);
        $response->assertSee('/fa/universities/lyon-3', false);

        // Assert FAQPage Schema
        $response->assertSee('"@type": "FAQPage"', false);
        $response->assertSee('بهترین دانشگاه لیون برای تحصیل دانشجویان ایرانی کدام است؟', false);

        // Assert no untranslated keys
        $response->assertDontSee('city/lyon.');
    }
}
