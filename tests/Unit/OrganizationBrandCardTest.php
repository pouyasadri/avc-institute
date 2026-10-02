<?php

namespace Tests\Unit;

use App\Services\StructuredData\LocalBusinessSchema;
use App\Services\StructuredData\OrganizationSchema;
use Tests\TestCase;

class OrganizationBrandCardTest extends TestCase
{
    public function test_seo_config_exposes_canonical_brand_card(): void
    {
        $org = config('seo.organization');

        $this->assertSame('A.V.C Institute', $org['name']);
        $this->assertSame('APPLY VIP CONSEIL', $org['legal_name']);
        $this->assertSame('+33768688326', $org['telephone']);
        $this->assertSame('+33 7 68 68 83 26', $org['telephone_display']);
        $this->assertSame('info@applyvipconseil.com', $org['email']);
        $this->assertContains('Apply VIP Conseil', $org['alternate_name']);
        $this->assertContains('موسسه A.V.C', $org['alternate_name']);
        $this->assertContains(
            'https://annuaire-entreprises.data.gouv.fr/entreprise/983675331',
            $org['same_as']
        );
        $this->assertSame(' | A.V.C Institute', config('seo.defaults.title_suffix'));
    }

    public function test_organization_schema_emits_name_aliases_and_phone(): void
    {
        $schema = (new OrganizationSchema)->build();

        $this->assertSame('A.V.C Institute', $schema['name']);
        $this->assertSame('APPLY VIP CONSEIL', $schema['legalName']);
        $this->assertSame('+33768688326', $schema['telephone']);
        $this->assertContains('Apply VIP Conseil', $schema['alternateName']);
        $this->assertContains(
            'https://annuaire-entreprises.data.gouv.fr/entreprise/983675331',
            $schema['sameAs']
        );
    }

    public function test_local_business_schema_mirrors_brand_card(): void
    {
        $schema = (new LocalBusinessSchema)->build();

        $this->assertSame('A.V.C Institute', $schema['name']);
        $this->assertSame('+33768688326', $schema['telephone']);
        $this->assertContains('Apply VIP Conseil', $schema['alternateName']);
    }

    public function test_llms_txt_brand_card_matches_canonical_facts(): void
    {
        $content = file_get_contents(public_path('llms.txt'));

        $this->assertStringContainsString('Canonical name: A.V.C Institute', $content);
        $this->assertStringContainsString('Legal name: APPLY VIP CONSEIL', $content);
        $this->assertStringContainsString('+33 7 68 68 83 26', $content);
        $this->assertStringNotContainsString('+33 7 80 95 33 33', $content);
    }
}
