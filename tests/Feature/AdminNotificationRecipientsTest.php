<?php

namespace Tests\Feature;

use App\Mail\ContactFormSubmitted;
use App\Mail\QuestionSubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminNotificationRecipientsTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_submission_notifies_primary_and_cc_admins(): void
    {
        Mail::fake();

        Config::set('mail.admin_primary', 'primary-admin@applyvipconseil.com');
        Config::set('mail.admin_cc', ['secondary-admin@applyvipconseil.com']);

        $payload = [
            'name' => 'Ali Reza',
            'email' => 'ali@example.com',
            'phone_number' => '09123456781',
            'msg_subject' => 'Inquiry regarding visa',
            'message' => 'Please provide information regarding student visas.',
            'gdpr_consent' => '1',
        ];

        $response = $this->from('/fa/contact')->post(route('contact.submit', ['locale' => 'fa']), $payload);

        $response->assertRedirect('/fa/contact');
        $response->assertSessionHas('success');

        Mail::assertQueued(ContactFormSubmitted::class, function (ContactFormSubmitted $mail) {
            return $mail->hasTo('primary-admin@applyvipconseil.com')
                && $mail->hasCc('secondary-admin@applyvipconseil.com');
        });
    }

    public function test_question_submission_notifies_primary_and_cc_admins(): void
    {
        Mail::fake();

        Config::set('mail.admin_primary', 'primary-admin@applyvipconseil.com');
        Config::set('mail.admin_cc', ['secondary-admin@applyvipconseil.com']);

        $payload = [
            'name' => 'Kaveh Rad',
            'email' => 'kaveh@example.com',
            'phone_number' => '09123456782',
            'msg_subject' => 'Question about university application',
            'message' => 'How can I apply to Sorbonne?',
            'page_type' => 'university',
            'page_name' => 'sorbonne',
            'gdpr_consent' => '1',
        ];

        $response = $this->from('/fa/university/sorbonne')->post(route('questions.submit', ['locale' => 'fa']), $payload);

        $response->assertRedirect('/fa/university/sorbonne');
        $response->assertSessionHas('success');

        Mail::assertQueued(QuestionSubmitted::class, function (QuestionSubmitted $mail) {
            return $mail->hasTo('primary-admin@applyvipconseil.com')
                && $mail->hasCc('secondary-admin@applyvipconseil.com');
        });
    }
}
