<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Step4GeoSeoAndParameterSanitizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_toulouse_city_page_renders_geo_capsule_cost_table_and_faq_schema(): void
    {
        $response = $this->get('/fa/cities/toulouse');

        $response->assertStatus(200);

        // Assert GEO capsule
        $response->assertSee('خلاصه راهنمای هزینه زندگی و مهاجرت تحصیلی به تولوز ۲۰۲۶', false);
        $response->assertSee('شهر صورتی و پایتخت هوافضای اروپا', false);

        // Assert Cost table
        $response->assertSee('جدول جزئیات هزینه‌های واقعی زندگی دانشجویی در تولوز ۲۰۲۶', false);
        $response->assertSee('اجاره مسکن و استودیو', false);
        $response->assertSee('حمل‌ونقل عمومی (Tisséo)', false);

        // Assert Targeted FAQs
        $response->assertSee('هزینه واقعی زندگی در تولوز فرانسه برای دانشجویان ایرانی چقدر است؟', false);
        $response->assertSee('آیا فارغ‌التحصیلان ایرانی در تولوز می‌توانند در شرکت‌های بزرگی مثل ایرباس (Airbus) استخدام شوند؟', false);

        // Assert FAQSchema JSON-LD
        $response->assertSee('"@type": "FAQPage"', false);
    }

    public function test_toulouse_university_page_renders_geo_capsule_tuition_and_faq_schema(): void
    {
        $response = $this->get('/fa/universities/toulouse');

        $response->assertStatus(200);

        // Assert GEO capsule
        $response->assertSee('خلاصه راهنمای پذیرش و شهریه دانشگاه تولوز ۲۰۲۶', false);
        $response->assertSee('تولوز ۱ کاپیتول', false);
        $response->assertSee('مدرسه اقتصاد جهانی TSE', false);

        // Assert Tuition & Exemption
        $response->assertSee('شهریه دانشگاه تولوز و معافیت شهریه تفکیکی ۲۰۲۶', false);

        // Assert FAQSchema JSON-LD
        $response->assertSee('"@type": "FAQPage"', false);
    }

    public function test_sciences_po_page_renders_geo_capsule_scholarships_and_faq_schema(): void
    {
        $response = $this->get('/fa/universities/sciences-po');

        $response->assertStatus(200);

        // Assert Persian title & GEO Capsule
        $response->assertSee('راهنمای جامع پذیرش موسسه مطالعات سیاسی پاریس (سیانس پو ۲۰۲۶)', false);
        $response->assertSee('موسسه مطالعات سیاسی پاریس (سیانس پو)', false);
        $response->assertSee('بورسیه شایستگی «امی بوتمی»', false);

        // Assert FAQ items
        $response->assertSee('شرایط قبولی دانشجویان ایرانی در موسسه مطالعات سیاسی پاریس (سیانس پو) چیست؟', false);

        // Assert FAQSchema JSON-LD
        $response->assertSee('"@type": "FAQPage"', false);
    }

    public function test_paris_cite_page_renders_geo_capsule_medical_pathways_and_faq_schema(): void
    {
        $response = $this->get('/fa/universities/paris-cite');

        $response->assertStatus(200);

        // Assert GEO capsule
        $response->assertSee('راهنمای جامع پذیرش دانشگاه پزشکی پاریس (دانشگاه پاریس سیته ۲۰۲۶)', false);
        $response->assertSee('دانشگاه پزشکی و علوم پاریس', false);

        // Assert PASS & L.AS pathways
        $response->assertSee('مسیرهای ورود به پزشکی در پاریس سیته (PASS و L.AS)', false);
        $response->assertSee('مسیر PASS (Parcours Accès Santé Spécifique)', false);
        $response->assertSee('مسیر L.AS (Licence Accès Santé)', false);

        // Assert FAQ items
        $response->assertSee('شرایط تحصیل پزشکی در دانشگاه پاریس سیته برای دانشجویان ایرانی چیست؟', false);

        // Assert FAQSchema JSON-LD
        $response->assertSee('"@type": "FAQPage"', false);
    }

    public function test_paris_city_page_renders_sciences_po_link_and_faq_schema(): void
    {
        $response = $this->get('/fa/cities/paris');

        $response->assertStatus(200);

        // Assert reciprocal links to Sciences Po and Paris Cité
        $response->assertSee('/fa/universities/sciences-po', false);
        $response->assertSee('/fa/universities/paris-cite', false);

        // Assert FAQSchema JSON-LD
        $response->assertSee('"@type": "FAQPage"', false);
    }

    public function test_cities_index_page_renders_conversational_faqs_and_faq_schema(): void
    {
        $response = $this->get('/fa/cities');

        $response->assertStatus(200);

        // Assert conversational questions
        $response->assertSee('بهترین شهر فرانسه برای تحصیل دانشجویان ایرانی در سال ۲۰۲۶ کدام است؟', false);
        $response->assertSee('ارزان‌ترین شهرهای فرانسه برای زندگی و تحصیل در سال ۲۰۲۶ کدامند؟', false);

        // Assert FAQSchema JSON-LD
        $response->assertSee('"@type": "FAQPage"', false);
    }

    public function test_parameter_sanitization_middleware_strips_dirty_query_params(): void
    {
        // Testing legacy dirty URLs found in GSC (e.g. view and status parameters)
        $dirtyUrl = '/en/universities/sciences-po?view=university.sciences-po&status=200';
        $response = $this->get($dirtyUrl);

        $response->assertStatus(301);
        $response->assertRedirect('/en/universities/sciences-po');

        // Testing dirty URL with another legitimate query param preserved
        $mixedUrl = '/en/cities/montpellier?view=city.montpellier&status=200&page=2';
        $mixedResponse = $this->get($mixedUrl);

        $mixedResponse->assertStatus(301);
        $mixedResponse->assertRedirect('/en/cities/montpellier?page=2');
    }
}
