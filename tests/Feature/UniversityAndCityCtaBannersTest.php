<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UniversityAndCityCtaBannersTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test university page in Persian renders the admission evaluation banner with correct links.
     */
    public function test_persian_university_page_renders_admission_evaluation_banner(): void
    {
        $response = $this->get('/fa/universities/lyon-2');

        $response->assertStatus(200);

        // Assert admission evaluation banner is rendered
        $response->assertSee('ارزیابی تخصصی شانس پذیرش و ویزای تحصیلی فرانسه');
        $response->assertSee('آیا شرایط اخذ پذیرش از این دانشگاه را دارید؟');
        $response->assertSee('بررسی تخصصی ددلاین‌های Campus France');
        $response->assertSee('استانداردسازی رزومه آکادمیک (CV)');
        $response->assertSee('شبیه‌سازی مصاحبه ویزای دانشجویی سفارت فرانسه');

        // Assert primary button links to consult with educational-counseling
        $response->assertSee('/fa/consult?service=educational-counseling');

        // Assert secondary WhatsApp button
        $response->assertSee('https://wa.me/33768688326');
        $response->assertSee('ارتباط مستقیم در واتساپ');

        // Assert sidebar CTA button
        $response->assertSee('ثبت درخواست ارزیابی');

        // Ensure no untranslated string keys remain
        $response->assertDontSee('cta.university.');
    }

    /**
     * Test multilingual university pages render properly.
     */
    public function test_multilingual_university_pages_render_evaluation_banner(): void
    {
        // English
        $enResponse = $this->get('/en/universities/lyon-2');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Do You Meet the Requirements for Admission to this Institution?');
        $enResponse->assertSee('Book an Admission Evaluation Session');
        $enResponse->assertDontSee('cta.university.');

        // French
        $frResponse = $this->get('/fr/universities/lyon-2');
        $frResponse->assertStatus(200);
        $frResponse->assertSee('Remplissez-vous les critères d\'admission pour cet établissement ?');
        $frResponse->assertSee('Prendre rendez-vous pour une évaluation');
        $frResponse->assertDontSee('cta.university.');
    }

    /**
     * Test city page in Persian renders the immigration and settlement banner with correct links.
     */
    public function test_persian_city_page_renders_immigration_settlement_banner(): void
    {
        $response = $this->get('/fa/cities/lyon');

        $response->assertStatus(200);

        // Assert city evaluation banner is rendered
        $response->assertSee('مشاوره جامع مهاجرت، اقامت و اسکان در فرانسه');
        $response->assertSee('برنامه‌ریزی و ارزیابی پرونده مهاجرت و اسکان در فرانسه');
        $response->assertSee('رزرو خوابگاه دانشجویی و مسکن با ضمانت دولتی ویزال (Visale)');
        $response->assertSee('بیمه سلامت Ameli و دریافت کارت اقامت (Titre de Séjour)');

        // Assert primary button links to consult with student-visa
        $response->assertSee('/fa/consult?service=student-visa');

        // Assert secondary WhatsApp button
        $response->assertSee('https://wa.me/33768688326');
        $response->assertSee('ارتباط مستقیم در واتساپ');

        // Assert sidebar CTA button
        $response->assertSee('رزرو وقت مشاوره');

        // Ensure no untranslated string keys remain
        $response->assertDontSee('cta.city.');
    }

    /**
     * Test multilingual city pages render properly.
     */
    public function test_multilingual_city_pages_render_settlement_banner(): void
    {
        // English
        $enResponse = $this->get('/en/cities/lyon');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Planning Your Relocation, Studies, or Residency in France');
        $enResponse->assertSee('Book Relocation & Visa Consultation');
        $enResponse->assertDontSee('cta.city.');

        // French
        $frResponse = $this->get('/fr/cities/lyon');
        $frResponse->assertStatus(200);
        $frResponse->assertSee('Organisez votre installation, vos études ou votre séjour en France');
        $frResponse->assertSee('Prendre rendez-vous conseil visa & séjour');
        $frResponse->assertDontSee('cta.city.');
    }

    /**
     * Test consult page pre-fills service and user_details from query parameters.
     */
    public function test_consult_page_prefills_service_and_details(): void
    {
        $response = $this->get('/fa/consult?service=educational-counseling&details='.urlencode('مشاوره پذیرش لیون'));

        $response->assertStatus(200);

        // Assert user_details textarea contains the prefilled text
        $response->assertSee('مشاوره پذیرش لیون');
        // Assert the educational service option is selected
        $response->assertSee('value="مشاوره تحصیل در فرانسه" selected', false);
    }
}
