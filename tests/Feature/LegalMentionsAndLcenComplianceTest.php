<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalMentionsAndLcenComplianceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test French Mentions Légales page satisfies LCEN Art. 6 and GDPR Art. 13 requirements.
     */
    public function test_french_legal_page_renders_lcen_and_gdpr_disclosures()
    {
        $response = $this->get('/fr/legal');

        $response->assertStatus(200);
        // LCEN: Director of publication
        $response->assertSee('Direction de la Publication');
        $response->assertSee('Directeur de la publication');

        // LCEN: Hosting provider
        $response->assertSee('Hébergement du Site');
        $response->assertSee('PlanetHoster');
        $response->assertSee('Datacenter Paris');

        // GDPR: DPO contact & CNIL
        $response->assertSee('dpo@applyvipconseil.com');
        $response->assertSee('CNIL');

        // Cross-links
        $response->assertSee(route('privacy', ['locale' => 'fr']));
        $response->assertSee(route('data-rights', ['locale' => 'fr']));
    }

    /**
     * Test English Legal page renders LCEN and GDPR disclosures.
     */
    public function test_english_legal_page_renders_lcen_and_gdpr_disclosures()
    {
        $response = $this->get('/en/legal');

        $response->assertStatus(200);
        $response->assertSee('Publication Direction');
        $response->assertSee('PlanetHoster');
        $response->assertSee('dpo@applyvipconseil.com');
        $response->assertSee(route('privacy', ['locale' => 'en']));
        $response->assertSee(route('data-rights', ['locale' => 'en']));
    }

    /**
     * Test Persian Legal page renders LCEN and GDPR disclosures.
     */
    public function test_persian_legal_page_renders_lcen_and_gdpr_disclosures()
    {
        $response = $this->get('/fa/legal');

        $response->assertStatus(200);
        $response->assertSee('مدیریت انتشار');
        $response->assertSee('PlanetHoster');
        $response->assertSee('dpo@applyvipconseil.com');
        $response->assertSee(route('privacy', ['locale' => 'fa']));
        $response->assertSee(route('data-rights', ['locale' => 'fa']));
    }

    /**
     * Test footer renders data-rights link alongside legal and privacy links.
     */
    public function test_footer_renders_legal_privacy_and_data_rights_links()
    {
        $response = $this->get('/fr');
        $response->assertStatus(200);

        $response->assertSee(route('legal', ['locale' => 'fr']));
        $response->assertSee(route('privacy', ['locale' => 'fr']));
        $response->assertSee(route('data-rights', ['locale' => 'fr']));
    }
}
