<?php

namespace Tests\Unit;

use App\Services\Discovery\AgentDiscoveryCatalog;
use Tests\TestCase;

class AgentDiscoveryCatalogTest extends TestCase
{
    public function test_agents_index_includes_brand_card_and_endpoints(): void
    {
        $index = app(AgentDiscoveryCatalog::class)->agentsIndex();

        $this->assertSame('A.V.C Institute', $index['name']);
        $this->assertSame('APPLY VIP CONSEIL', $index['legalName']);
        $this->assertSame('fa', $index['primaryLocale']);
        $this->assertSame(url('/llms.txt'), $index['documentation']);
        $this->assertNotEmpty($index['endpoints']);
        $this->assertTrue(collect($index['endpoints'])->contains(
            fn (array $endpoint) => ($endpoint['rel'] ?? null) === 'service-doc'
        ));
    }

    public function test_api_catalog_links_agents_index_and_llms(): void
    {
        $catalog = app(AgentDiscoveryCatalog::class)->apiCatalog();
        $entry = $catalog['linkset'][0];

        $this->assertSame(url('/llms.txt'), $entry['service-doc'][0]['href']);
        $this->assertSame(url('/.well-known/agents-index.json'), $entry['describedby'][0]['href']);
        $this->assertSame(url('/sitemap.xml'), $entry['sitemap'][0]['href']);
    }
}
