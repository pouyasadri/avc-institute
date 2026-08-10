<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactFormConfirmation;
use App\Mail\ContactFormSubmitted;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(StoreContactRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        try {
            $name = $validatedData['name'];
            $email = $validatedData['email'];
            $phoneNumber = $validatedData['phone_number'];
            $subject = $validatedData['msg_subject'];
            $message = $validatedData['message'];

            $visaTypeKey = $validatedData['visa_type'] ?? null;
            if ($visaTypeKey) {
                $visaLabel = __('contact.form.visa_type.options.'.$visaTypeKey);
                if (is_string($visaLabel)) {
                    $subject = '['.$visaLabel.'] '.$subject;
                    $message = 'Visa Category / Target: '.$visaLabel."\n\n".$message;
                }
            }

            // Save to database
            $submission = ContactSubmission::create([
                'name' => $name,
                'email' => $email,
                'phone_number' => $phoneNumber,
                'subject' => $subject,
                'message' => $message,
                'locale' => app()->getLocale(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            // Send Confirmation Email to User
            Mail::to($email)
                ->locale(app()->getLocale())
                ->send(new ContactFormConfirmation($submission));

            // Send Notification Email to Admin
            $adminEmail = 'info@applyvipconseil.com';
            Mail::to($adminEmail)
                ->locale('fa')
                ->send(new ContactFormSubmitted($submission));

            return redirect()->back()->with('success', __('messages.contact_success'));
        } catch (\Exception $e) {
            Log::error('Error in contact form submission: '.$e->getMessage());

            return redirect()->back()->with('error', __('messages.contact_error'));
        }
    }
}
