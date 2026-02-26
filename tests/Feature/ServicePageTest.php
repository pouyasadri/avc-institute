<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServicePageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the services index page loads correctly.
     */
    public function test_services_index_page_loads_correctly()
    {
        $response = $this->get('/en/services');
        $response->assertStatus(200);
        $response->assertViewIs('pages.services.index');
    }

    /**
     * Test that an existing service page loads correctly.
     */
    public function test_existing_service_page_loads_correctly()
    {
        $response = $this->get('/en/services/residence-permit');
        $response->assertStatus(200);
        $response->assertViewIs('pages.services.show');
        $response->assertSeeText('Residence Permit & Immigration Assistance'); // Updated title
    }

    /**
     * Test that an invalid service slug redirects.
     */
    public function test_invalid_service_slug_redirects()
    {
        $response = $this->get('/en/services/invalid-service-slug');

        $response->assertStatus(302); // Should redirect back to index
        $response->assertRedirect('/en');
    }

    /**
     * Test service page in another locale (e.g., French).
     */
    public function test_service_page_in_french()
    {
        $response = $this->get('/fr/services/residence-permit');

        $response->assertStatus(200);
        $response->assertSeeText('Assistance Titre de Séjour & Immigration');
    }

    /**
     * Test CV & Motivation Letter page content.
     */
    public function test_resume_service_page_loads_correctly()
    {
        // English
        $responseEn = $this->get('/en/services/resume-lettre-motivation');
        $responseEn->assertStatus(200);
        $responseEn->assertSeeText('CV & Motivation Letter (Lettre de Motivation)');

        // French
        $responseFr = $this->get('/fr/services/resume-lettre-motivation');
        $responseFr->assertStatus(200);
        $responseFr->assertSeeText('CV & Lettre de Motivation');
    }

    /**
     * Test Arrival Support page content.
     */
    public function test_arrival_service_page_loads_correctly()
    {
        // English
        $responseEn = $this->get('/en/services/arrival-support');
        $responseEn->assertStatus(200);
        $responseEn->assertSeeText('Settling-In & Arrival Support');

        // French
        $responseFr = $this->get('/fr/services/arrival-support');
        $responseFr->assertStatus(200);
        $responseFr->assertSeeText('Accompagnement à l\'Installation & Arrivée');
    }

    /**
     * Test Certified Translation page content.
     */
    public function test_translation_service_page_loads_correctly()
    {
        // English
        $responseEn = $this->get('/en/services/certified-translation');
        $responseEn->assertStatus(200);
        $responseEn->assertSeeText('Certified Sworn Translation (Traduction Assermentée)');

        // French
        $responseFr = $this->get('/fr/services/certified-translation');
        $responseFr->assertStatus(200);
        $responseFr->assertSeeText('Traduction Assermentée (Certifiée)');
    }

    /**
     * Test Educational Counseling page content.
     */
    public function test_educational_service_page_loads_correctly()
    {
        // English
        $responseEn = $this->get('/en/services/educational-counseling');
        $responseEn->assertStatus(200);
        $responseEn->assertSeeText('Strategic Educational Counseling & Career Roadmap');

        // French
        $responseFr = $this->get('/fr/services/educational-counseling');
        $responseFr->assertStatus(200);
        $responseFr->assertSeeText('Conseil Éducatif Stratégique & Plan de Carrière');
    }

    /**
     * Test Housing Assistance page content.
     */
    public function test_housing_service_page_loads_correctly()
    {
        // English
        $responseEn = $this->get('/en/services/housing-assistance');
        $responseEn->assertStatus(200);
        $responseEn->assertSeeText('Housing Search & CAF Housing Aid');

        // French
        $responseFr = $this->get('/fr/services/housing-assistance');
        $responseFr->assertStatus(200);
        $responseFr->assertSeeText('Recherche de Logement & Aide CAF');
    }

    /**
     * Test University Application page content.
     */
    public function test_university_service_page_loads_correctly()
    {
        // English
        $responseEn = $this->get('/en/services/university-application');
        $responseEn->assertStatus(200);
        $responseEn->assertSeeText('Admission & Platform Management (Parcoursup & Campus France)');

        // French
        $responseFr = $this->get('/fr/services/university-application');
        $responseFr->assertStatus(200);
        $responseFr->assertSeeText('Admission & Gestion de Plateformes (Parcoursup & Campus France)');
    }

    /**
     * Test Administrative Advocacy page content.
     */
    public function test_advocacy_service_page_loads_correctly()
    {
        // English
        $responseEn = $this->get('/en/services/administrative-advocacy');
        $responseEn->assertStatus(200);
        $responseEn->assertSeeText('Administrative Advocacy & Liaison');

        // French
        $responseFr = $this->get('/fr/services/administrative-advocacy');
        $responseFr->assertStatus(200);
        $responseFr->assertSeeText('Plaidoyer Administratif & Liaison');
    }

    /**
     * Test Legal Support page content.
     */
    public function test_legal_service_page_loads_correctly()
    {
        // English
        $responseEn = $this->get('/en/services/legal-support');
        $responseEn->assertStatus(200);
        $responseEn->assertSeeText('Legal & Litigation Support (Public Law Experts)');

        // French
        $responseFr = $this->get('/fr/services/legal-support');
        $responseFr->assertStatus(200);
        $responseFr->assertSeeText('Support Juridique & Contentieux (Droit Public)');
    }
}
