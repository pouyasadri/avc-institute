<?php

namespace App\Http\Controllers;

use App\Mail\DataRightsConfirmation;
use App\Mail\DataRightsNotification;
use App\Models\DataRightsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'email' => 'required|email|max:255',
            'request_type' => 'required|in:access,rectification,erasure,portability,objection,restriction',
            'notes_requester' => 'nullable|string|max:2000',
            'gdpr_consent' => 'required|accepted',
        ]);

        try {
            $locale = app()->getLocale();

            // Generate a unique secure token (used in emailed links)
            $token = hash('sha256', uniqid($validated['email'], true).random_bytes(16));

            $dataRequest = DataRightsRequest::create([
                'email' => $validated['email'],
                'request_type' => $validated['request_type'],
                'status' => 'pending',
                'token' => $token,
                'notes_requester' => $validated['notes_requester'] ?? null,
                'locale' => $locale,
                'ip_address' => $request->ip(),
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
            Log::error('DataRightsController@submitRequest failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', __('privacy.data_rights.form.error'));
        }

        return redirect()->back()->with('success', __('privacy.data_rights.form.success'));
    }
}
