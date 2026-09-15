<?php

namespace Tests\Feature;

use App\Mail\ConsultationConfirmation;
use App\Mail\ConsultationSubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ConsultFormDuplicateTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_consultation_submission_creates_record_and_sends_emails(): void
    {
        Mail::fake();

        $payload = [
            'user_name' => 'Maryam Rahimi',
            'user_email' => 'maryrahimi252@gmail.com',
            'user_phone_number' => '09123456789',
            'user_service' => 'France Study Consultation',
            'user_details' => 'I want to study in France.',
            'gdpr_consent' => '1',
        ];

        $response = $this->from('/fa/consult')->post(route('consult.submit', ['locale' => 'fa']), $payload);

        $response->assertRedirect('/fa/consult');
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('consulting_submissions', 1);
        $this->assertDatabaseHas('consulting_submissions', [
            'email' => 'maryrahimi252@gmail.com',
            'service' => 'France Study Consultation',
        ]);

        Mail::assertQueued(ConsultationConfirmation::class, 1);
        Mail::assertQueued(ConsultationSubmitted::class, 1);
    }

    public function test_immediate_duplicate_submission_is_prevented_idempotently(): void
    {
        Mail::fake();

        $payload = [
            'user_name' => 'Maryam Rahimi',
            'user_email' => 'maryrahimi252@gmail.com',
            'user_phone_number' => '09123456789',
            'user_service' => 'France Study Consultation',
            'user_details' => 'I want to study in France.',
            'gdpr_consent' => '1',
        ];

        // First submission
        $firstResponse = $this->from('/fa/consult')->post(route('consult.submit', ['locale' => 'fa']), $payload);
        $firstResponse->assertRedirect('/fa/consult');
        $firstResponse->assertSessionHas('success');

        // Immediate second submission (user double-clicking or resubmitting identical form)
        $secondResponse = $this->from('/fa/consult')->post(route('consult.submit', ['locale' => 'fa']), $payload);
        $secondResponse->assertRedirect('/fa/consult');
        $secondResponse->assertSessionHas('success');

        // Verify only 1 record exists in database
        $this->assertDatabaseCount('consulting_submissions', 1);

        // Verify no duplicate emails were dispatched on second submission
        Mail::assertQueued(ConsultationConfirmation::class, 1);
        Mail::assertQueued(ConsultationSubmitted::class, 1);
    }

    public function test_submission_for_different_service_or_email_is_allowed(): void
    {
        Mail::fake();

        $firstPayload = [
            'user_name' => 'Maryam Rahimi',
            'user_email' => 'maryrahimi252@gmail.com',
            'user_phone_number' => '09123456789',
            'user_service' => 'France Study Consultation',
            'user_details' => 'I want to study in France.',
            'gdpr_consent' => '1',
        ];

        $secondPayload = [
            'user_name' => 'Maryam Rahimi',
            'user_email' => 'maryrahimi252@gmail.com',
            'user_phone_number' => '09123456789',
            'user_service' => 'France Residence Consultation',
            'user_details' => 'I also have questions about residence.',
            'gdpr_consent' => '1',
        ];

        $this->from('/fa/consult')->post(route('consult.submit', ['locale' => 'fa']), $firstPayload);
        $this->from('/fa/consult')->post(route('consult.submit', ['locale' => 'fa']), $secondPayload);

        $this->assertDatabaseCount('consulting_submissions', 2);
    }
}
