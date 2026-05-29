<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Mail\QuestionConfirmation;
use App\Mail\QuestionSubmitted;
use App\Models\QuestionSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class QuestionController extends Controller
{
    public function submit(StoreQuestionRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        try {
            $name = $validatedData['name'];
            $email = $validatedData['email'];
            $phoneNumber = $validatedData['phone_number'];
            $subject = $validatedData['msg_subject'];
            $message = $validatedData['message'];
            $pageType = $validatedData['page_type'];
            $pageName = $validatedData['page_name'];

            // Save to database
            $submission = QuestionSubmission::create([
                'name' => $name,
                'email' => $email,
                'phone_number' => $phoneNumber,
                'subject' => $subject,
                'message' => $message,
                'page_type' => $pageType,
                'page_name' => $pageName,
                'locale' => app()->getLocale(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Send Confirmation Email to User
            Mail::to($email)
                ->locale(app()->getLocale())
                ->send(new QuestionConfirmation($submission));

            // Send Notification Email to Admin
            $adminEmail = 'info@applyvipconseil.com';
            Mail::to($adminEmail)
                ->locale('fa')
                ->send(new QuestionSubmitted($submission));

            return redirect()->back()->with('success', __('messages.question_success'));
        } catch (\Exception $e) {
            Log::error('Error in question form submission: '.$e->getMessage());

            return redirect()->back()->with('error', __('messages.question_error'));
        }
    }
}
