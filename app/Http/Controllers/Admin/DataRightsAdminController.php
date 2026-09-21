<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\ConsultingSubmission;
use App\Models\ContactSubmission;
use App\Models\DataRightsRequest;
use App\Models\QuestionSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DataRightsAdminController extends Controller
{
    /**
     * List all data-subject rights requests, filterable by status.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');

        $requests = DataRightsRequest::when(
            in_array($status, ['pending', 'completed', 'rejected'], true),
            fn ($q) => $q->where('status', $status)
        )
            ->latest()
            ->paginate(20);

        $counts = [
            'pending' => DataRightsRequest::where('status', 'pending')->count(),
            'completed' => DataRightsRequest::where('status', 'completed')->count(),
            'rejected' => DataRightsRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.data-rights.index', compact('requests', 'status', 'counts'));
    }

    /**
     * Show a single data-rights request with matched submissions.
     */
    public function show(DataRightsRequest $dataRight): View
    {
        $email = $dataRight->email;

        $matchedData = [
            'contact' => ContactSubmission::where('email', $email)->latest()->get(),
            'consulting' => ConsultingSubmission::where('email', $email)->latest()->get(),
            'questions' => QuestionSubmission::where('email', $email)->latest()->get(),
            'comments' => Comment::where('email', $email)->latest()->get(),
        ];

        return view('admin.data-rights.show', compact('dataRight', 'matchedData'));
    }

    /**
     * Update the status of a request (completed / rejected) with optional admin notes.
     */
    public function update(Request $request, DataRightsRequest $dataRight): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:completed,rejected',
            'notes_admin' => 'nullable|string|max:2000',
        ]);

        try {
            $dataRight->update([
                'status' => $validated['status'],
                'notes_admin' => $validated['notes_admin'],
                'completed_at' => now(),
            ]);

            Log::info("GDPR data-rights request {$dataRight->id} marked as {$validated['status']} by admin.");

        } catch (\Exception $e) {
            Log::error('DataRightsAdminController@update failed: '.$e->getMessage());

            return redirect()->back()->with('error', 'Update failed — check logs.');
        }

        return redirect()
            ->route('admin.data-rights.show', $dataRight->id)
            ->with('success', 'Request updated successfully.');
    }

    /**
     * Confirm erasure and soft-delete all submissions and comments for the requester (Art. 17).
     *
     * Only available from the admin panel (not a public endpoint).
     */
    public function eraseData(DataRightsRequest $dataRight): RedirectResponse
    {
        try {
            $email = $dataRight->email;

            ContactSubmission::where('email', $email)->delete();
            ConsultingSubmission::where('email', $email)->delete();
            QuestionSubmission::where('email', $email)->delete();
            Comment::where('email', $email)->delete();

            $dataRight->update([
                'status' => 'completed',
                'completed_at' => now(),
                'notes_admin' => ($dataRight->notes_admin ?? '').
                    "\n[ERASURE] Data erased by admin on ".now()->toDateTimeString(),
            ]);

            Log::info("GDPR Art. 17: data erased for request {$dataRight->id} (hash: ".hash('sha256', $email).') — triggered by admin.');

        } catch (\Exception $e) {
            Log::error('DataRightsAdminController@eraseData failed: '.$e->getMessage());

            return redirect()->back()->with('error', 'Erasure failed — check logs.');
        }

        return redirect()
            ->route('admin.data-rights.index')
            ->with('success', "All submissions and comments for {$dataRight->email} have been soft-deleted.");
    }

    /**
     * Export all personal data held for a requester in machine-readable JSON format (Art. 15 / Art. 20).
     */
    public function exportData(DataRightsRequest $dataRight): Response
    {
        $email = $dataRight->email;

        $export = [
            'generated_at' => now()->toIso8601String(),
            'gdpr_article' => 'Art. 15 (Access) & Art. 20 (Data Portability)',
            'data_controller' => 'ApplyVIP Conseil (A.V.C Institute), 67000 Strasbourg, France',
            'requester' => [
                'email' => $email,
                'request_id' => $dataRight->id,
            ],
            'contact_submissions' => ContactSubmission::where('email', $email)
                ->get(['name', 'email', 'phone_number', 'subject', 'message', 'locale', 'created_at'])
                ->toArray(),
            'consulting_submissions' => ConsultingSubmission::where('email', $email)
                ->get(['name', 'email', 'phone_number', 'service', 'details', 'locale', 'created_at'])
                ->toArray(),
            'question_submissions' => QuestionSubmission::where('email', $email)
                ->get(['name', 'email', 'phone_number', 'subject', 'message', 'page_type', 'page_name', 'locale', 'created_at'])
                ->toArray(),
            'comments' => Comment::where('email', $email)
                ->get(['name', 'email', 'subject', 'body', 'created_at'])
                ->toArray(),
        ];

        return response(json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="avc-data-export-'.$dataRight->id.'.json"',
        ]);
    }
}
