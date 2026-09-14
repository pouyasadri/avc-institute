<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MontpellierMarseilleClusterTest extends TestCase
{
    use RefreshDatabase;

    public function test_montpellier_city_hub_renders_cleanly_with_geo_capsule_and_cluster_links(): void
    {
        $response = $this->get('/fa/cities/montpellier');

        $response->assertStatus(200);
        $response->assertSee('جدول جزئیات هزینه‌های واقعی زندگی دانشجویی در مونپلیه ۲۰۲۶');
        $response->assertSee('اطلاعات کلیدی مونپلیه ۲۰۲۶ (در یک نگاه)');
        $response->assertSee('universities/universite-de-montpellier');
        $response->assertSee('iranians-in-montpellier');
        $response->assertSee('cities/marseille');
        $response->assertSee('cities/toulouse');
        $response->assertSee('cities/lyon');
        $response->assertSee('consult?service=student-visa');
        $response->assertSee('FAQPage');
    }

    public function test_marseille_city_hub_renders_cleanly_with_geo_capsule_and_cluster_links(): void
    {
        $response = $this->get('/fa/cities/marseille');

        $response->assertStatus(200);
        $response->assertSee('جدول جزئیات هزینه‌های واقعی زندگی دانشجویی در مارسی ۲۰۲۶');
        $response->assertSee('اطلاعات کلیدی مارسی ۲۰۲۶ (در یک نگاه)');
        $response->assertSee('universities/aix-marseille-university');
        $response->assertSee('iranians-in-marseille');
        $response->assertSee('cities/montpellier');
        $response->assertSee('cities/nice');
        $response->assertSee('cities/lyon');
        $response->assertSee('consult?service=student-visa');
        $response->assertSee('FAQPage');
    }

    public function test_universite_de_montpellier_page_links_to_montpellier_city_guide_and_consult(): void
    {
        $response = $this->get('/fa/universities/universite-de-montpellier');

        $response->assertStatus(200);
        $response->assertSee('cities/montpellier');
        $response->assertSee('consult?service=student-visa');
    }

    public function test_aix_marseille_university_page_links_to_marseille_city_guide_and_consult(): void
    {
        $response = $this->get('/fa/universities/aix-marseille-university');

        $response->assertStatus(200);
        $response->assertSee('cities/marseille');
        $response->assertSee('consult?service=student-visa');
    }
}
