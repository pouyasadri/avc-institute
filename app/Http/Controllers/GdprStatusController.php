<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class GdprStatusController extends Controller
{
    public function show()
    {
        $policyVersion = config('gdpr.privacy_policy_version');
        $retentionDays = config('gdpr.retention_days');
        $techRetention = config('gdpr.technical_retention_days');
        $dpoEmail = config('gdpr.dpo_email');
        $authUrl = config('gdpr.supervisory_authority_url');

        // Note: DataRightsRequest count is not exposed publicly
        $lastPurge = null;
        if (file_exists(storage_path('logs/gdpr-purge.log'))) {
            $lastPurge = Carbon::createFromTimestamp(filemtime(storage_path('logs/gdpr-purge.log')))->toIso8601String();
        }

        return view('pages.privacy-status', compact(
            'policyVersion',
            'retentionDays',
            'techRetention',
            'dpoEmail',
            'authUrl',
            'lastPurge'
        ));
    }
}
