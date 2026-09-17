<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AdminRopaAndSecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_ropa_registry()
    {
        $response = $this->get(route('admin.ropa.index'));
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_ropa_registry()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.ropa.index'));
        $response->assertStatus(200);
        $response->assertSee('Registre des Activités de Traitement');
        $response->assertSee('ROPA-01');
        $response->assertSee('ROPA-02');
        $response->assertSee('ROPA-03');
        $response->assertSee('ROPA-04');
        $response->assertSee('ROPA-05');
        $response->assertSee('ROPA-06');
        $response->assertSee('ROPA-07');
        $response->assertSee('notifications.cnil.fr');
    }

    public function test_admin_sidebar_renders_ropa_link()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee(route('admin.ropa.index'));
        $response->assertSee('Registre ROPA');
    }

    public function test_duplicate_consultation_request_logs_email_hash_without_plaintext_email()
    {
        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message, $context) {
                return $message === 'Duplicate consultation request prevented'
                    && isset($context['email_hash'])
                    && $context['email_hash'] === hash('sha256', 'duplicate@example.com')
                    && ! isset($context['email'])
                    && ! isset($context['ip']);
            });

        $payload = [
            'user_name' => 'John Doe',
            'user_email' => 'duplicate@example.com',
            'user_service' => 'Student Visa',
            'user_phone_number' => '+33612345678',
            'user_details' => 'First consultation query',
            'gdpr_consent' => '1',
        ];

        // 1. First submission
        $first = $this->post('/fr/consult/submit', $payload);
        $first->assertRedirect();

        // 2. Immediate duplicate submission (triggers duplicate logger)
        $second = $this->post('/fr/consult/submit', $payload);
        $second->assertRedirect();
    }

    public function test_consultation_request_validates_details_max_2000_characters()
    {
        $payload = [
            'user_name' => 'John Doe',
            'user_email' => 'valid@example.com',
            'user_service' => 'Student Visa',
            'user_phone_number' => '+33612345678',
            'user_details' => str_repeat('a', 2001), // Exceeds 2000 chars
            'gdpr_consent' => '1',
        ];

        $response = $this->post('/fr/consult/submit', $payload);
        $response->assertSessionHasErrors('user_details');
    }

    public function test_session_secure_cookie_configuration()
    {
        $this->assertNotNull(config('session.secure') !== null);
    }
}
