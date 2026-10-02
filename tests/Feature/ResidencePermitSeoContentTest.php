<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidencePermitSeoContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_persian_residence_permit_page_covers_renewal_and_2026_rules(): void
    {
        $response = $this->get('/fa/services/residence-permit');

        $response->assertOk();
        $response->assertSee('تمدید کارت اقامت فرانسه', false);
        $response->assertSee('سامانه ANEF', false);
        $response->assertSee('رسیپیسه', false);
        $response->assertSee('آزمون مدنی', false);
        $response->assertSee('service-public.fr/particuliers/vosdroits/F39530', false);
        $response->assertSee('معمولاً لازم نیست', false);
        $response->assertSee('azmoon-madani-france-2026', false);
    }

    public function test_english_residence_permit_page_states_renewal_exemption(): void
    {
        $response = $this->get('/en/services/residence-permit');

        $response->assertOk();
        $response->assertSee('Renew French Residence Permit 2026', false);
        $response->assertSee('ANEF', false);
        $response->assertSee('civic exam', false);
        $response->assertSee('does not require the civic exam', false);
    }

    public function test_consult_page_targets_study_consultation_query(): void
    {
        $response = $this->get('/fa/consult');

        $response->assertOk();
        $response->assertSee('مشاوره تحصیل در فرانسه', false);
        $response->assertSee('بدون شعار تضمین ویزا', false);
    }
}
