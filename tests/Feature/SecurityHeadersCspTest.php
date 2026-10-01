<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityHeadersCspTest extends TestCase
{
    use RefreshDatabase;

    public function test_csp_img_src_includes_configured_object_storage_host(): void
    {
        config([
            'filesystems.disks.s3.url' => 'https://cdn.example-storage.test/bucket',
        ]);

        $response = $this->get('/en');

        $response->assertOk();
        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertNotNull($csp);
        $this->assertStringContainsString('img-src', $csp);
        $this->assertStringContainsString('https://cdn.example-storage.test', $csp);
    }
}
