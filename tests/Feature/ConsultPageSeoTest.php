<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConsultPageSeoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test /fa/consult page loads, contains target striking distance keywords and GEO capsule.
     */
    public function test_fa_consult_page_has_striking_distance_keywords_and_geo_capsule(): void
    {
        $response = $this->get('/fa/consult');

        $response->assertStatus(200);

        // Assert striking distance commercial keywords in HTML
        $response->assertSee('موسسه مهاجرتی فرانسه', false);
        $response->assertSee('مشاوره تحصیل در فرانسه', false);
        $response->assertSee('وکیل مهاجرت به فرانسه', false);

        // Assert GEO answer capsule is present
        $response->assertSee('موسسه بین‌المللی A.V.C با بیش از ۱۰ سال سابقه تخصصی', false);

        // Assert SLA commitment and WhatsApp button
        $response->assertSee('تعهد سرعت و پاسخگویی A.V.C', false);
        $response->assertSee('https://wa.me/33768688326', false);

        // Assert Schema.org JSON-LD elements
        $response->assertSee('"@type": "BreadcrumbList"', false);
        $response->assertSee('"@type": "FAQPage"', false);
        $response->assertSee('EducationalOrganization', false);
        $response->assertSee('LegalService', false);
    }

    /**
     * Test university pages include keyword-rich internal anchor to /fa/consult.
     */
    public function test_university_pages_link_to_consult_with_target_anchor(): void
    {
        $response = $this->get('/fa/universities/lyon-2');

        $response->assertStatus(200);
        $response->assertSee('/fa/consult?service=educational-counseling', false);
        $response->assertSee('مشاوره تحصیل در فرانسه و اخذ پذیرش', false);
    }

    /**
     * Test city pages include keyword-rich internal anchor to /fa/consult.
     */
    public function test_city_pages_link_to_consult_with_target_anchor(): void
    {
        $response = $this->get('/fa/cities/lyon');

        $response->assertStatus(200);
        $response->assertSee('/fa/consult?service=student-visa', false);
        $response->assertSee('رزرو وقت در موسسه مهاجرتی فرانسه (A.V.C)', false);
    }
}
