<?php

namespace App\Http\Controllers;

use App\Mail\DataRightsConfirmation;
use App\Mail\DataRightsNotification;
use App\Models\ContactSubmission;
use App\Models\ConsultingSubmission;
use App\Models\DataRightsRequest;
use App\Models\QuestionSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DataRightsController extends Controller
{
    /**
     * Show the data-subject rights request form (Art. 12 GDPR).
     */
    public function showForm(): View
    {
        return view('pages.data-rights');
    }

    /**
     * Accept and process a new data-subject rights request.
     *
     * Throttled by 'data-rights-form' rate limiter (3 attempts per hour per IP).
     */
    public function submitRequest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email'            => 'required|email|max:255',
            'request_type'     => 'required|in:access,rectification,erasure,portability,objection,restriction',
            'notes_requester'  => 'nullable|string|max:2000',
            'gdpr_consent'     => 'required|accepted',
        ]);

        try {
            $locale = app()->getLocale();

            // Generate a unique secure token (used in emailed links)
            $token = hash('sha256', uniqid($validated['email'], true) . random_bytes(16));

            $dataRequest = DataRightsRequest::create([
                'email'           => $validated['email'],
                'request_type'    => $validated['request_type'],
                'status'          => 'pending',
                'token'           => $token,
                'notes_requester' => $validated['notes_requester'] ?? null,
                'locale'          => $locale,
                'ip_address'      => $request->ip(),
            ]);

            // Notify requester
            Mail::to($validated['email'])->queue(
                new DataRightsConfirmation($dataRequest)
            );

            // Notify DPO
            Mail::to(config('gdpr.dpo_email', 'dpo@applyvipconseil.com'))->queue(
                new DataRightsNotification($dataRequest)
            );

        } catch (\Exception $e) {
            Log::error('DataRightsController@submitRequest failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', __('privacy.data_rights.form.error'));
        }

        return redirect()->back()->with('success', __('privacy.data_rights.form.success'));
    }

    /**
     * Generate a portable data export (JSON) for a requester identified by token (Art. 20).
     *
     * Token is validated — only the requester who received the email can access this URL.
     */
    public function exportData(string $token): Response
    {
        $dataRequest = DataRightsRequest::where('token', $token)
            ->where('request_type', 'portability')
            ->where('status', 'completed')
            ->firstOrFail();

        $email = $dataRequest->email;

        $export = [
            'generated_at'       => now()->toIso8601String(),
            'gdpr_article'       => 'Art. 20 — Right to Data Portability',
            'data_controller'    => 'ApplyVIP Conseil (A.V.C Institute), 67000 Strasbourg, France',
            'contact_submissions' => ContactSubmission::where('email', $email)
                ->get(['name', 'email', 'phone_number', 'subject', 'message', 'locale', 'created_at'])
                ->toArray(),
            'consulting_submissions' => ConsultingSubmission::where('email', $email)
                ->get(['name', 'email', 'phone_number', 'service', 'details', 'locale', 'created_at'])
                ->toArray(),
            'question_submissions' => QuestionSubmission::where('email', $email)
                ->get(['name', 'email', 'phone_number', 'subject', 'message', 'page_type', 'page_name', 'locale', 'created_at'])
                ->toArray(),
        ];

        return response(json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => 'attachment; filename="avc-data-export-' . now()->format('Y-m-d') . '.json"',
        ]);
    }

    /**
     * Confirm erasure of a requester's data (Art. 17) — admin-triggered only.
     *
     * This endpoint is only accessible from the admin data-rights panel.
     * It performs a soft-delete cascade across all submission tables for the given email.
     */
    public function deleteData(string $token): RedirectResponse
    {
        $dataRequest = DataRightsRequest::where('token', $token)
            ->where('request_type', 'erasure')
            ->firstOrFail();

        try {
            DB::transaction(function () use ($dataRequest) {
                $email = $dataRequest->email;

                // Soft-delete across all submission tables (preserves audit log for 30 days)
                ContactSubmission::where('email', $email)->delete();
                ConsultingSubmission::where('email', $email)->delete();
                QuestionSubmission::where('email', $email)->delete();

                // Mark request as completed
                $dataRequest->update([
                    'status'       => 'completed',
                    'completed_at' => now(),
                    'notes_admin'  => 'Data erased via Art. 17 erasure request. All submissions soft-deleted on ' . now()->toDateTimeString(),
                ]);
            });

            Log::info('GDPR Art. 17: data erased for ' . $dataRequest->email);

        } catch (\Exception $e) {
            Log::error('DataRightsController@deleteData failed: ' . $e->getMessage());

            return redirect()->route('admin.data-rights.index')
                ->with('error', 'Erasure failed — check logs.');
        }

        return redirect()->route('admin.data-rights.index')
            ->with('success', 'Data erased and request marked as completed.');
    }
}
