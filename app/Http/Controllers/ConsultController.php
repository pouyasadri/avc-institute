<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultingRequest;
use App\Mail\ConsultationConfirmation;
use App\Mail\ConsultationSubmitted;
use App\Models\ConsultingSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ConsultController extends Controller
{
    public function submit(StoreConsultingRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        try {
            $userName = $validatedData['user_name'];
            $userEmail = $validatedData['user_email'];
            $userService = $validatedData['user_service'];
            $userDetails = $validatedData['user_details'];
            $userPhoneNumber = $validatedData['user_phone_number'];

            // Prevent duplicate submissions within a 2-minute window (idempotent handling)
            $recentSubmission = ConsultingSubmission::where('email', $userEmail)
                ->where('service', $userService)
                ->where('created_at', '>=', now()->subMinutes(2))
                ->first();

            if ($recentSubmission) {
                Log::info('Duplicate consultation request prevented', [
                    'email' => $userEmail,
                    'service' => $userService,
                    'ip' => $request->ip(),
                ]);

                return redirect()->back()->with('success', __('messages.consult_success'));
            }

            // Save to database
            $submission = ConsultingSubmission::create([
                'name' => $userName,
                'email' => $userEmail,
                'phone_number' => $userPhoneNumber,
                'service' => $userService,
                'details' => $userDetails,
                'locale' => app()->getLocale(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Send Confirmation Email to User
            Mail::to($userEmail)
                ->locale(app()->getLocale())
                ->send(new ConsultationConfirmation([
                    'user_name' => $userName,
                    'user_service' => $userService,
                    'user_phone_number' => $userPhoneNumber,
                    'user_details' => $userDetails,
                ]));

            // Send Notification Email to Admin
            $adminEmail = 'info@applyvipconseil.com';
            Mail::to($adminEmail)
                ->locale('fa')
                ->send(new ConsultationSubmitted([
                    'user_name' => $userName,
                    'user_email' => $userEmail, // Included in admin email for reply-to reference
                    'user_service' => $userService,
                    'user_phone_number' => $userPhoneNumber,
                    'user_details' => $userDetails,
                ]));

            return redirect()->back()->with('success', __('messages.consult_success'));
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error in submitting consultation request: '.$e->getMessage());

            return redirect()->back()->with('error', __('messages.consult_error'));
        }
    }
}
