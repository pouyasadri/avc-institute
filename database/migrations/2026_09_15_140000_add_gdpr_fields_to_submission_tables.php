<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add GDPR consent fields to all submission tables.
     *
     * Tables altered:
     *  - contact_submissions
     *  - question_submissions
     *  - consulting_submissions
     *  - comments (for future comment-with-consent flows)
     */
    public function up(): void
    {
        $tables = [
            'contact_submissions',
            'question_submissions',
            'consulting_submissions',
            'comments',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                // Was consent given at form submission?
                $t->boolean('gdpr_consent')->default(false)->after('user_agent');

                // Timestamp of when consent was recorded
                $t->timestamp('consent_given_at')->nullable()->after('gdpr_consent');

                // Version of the privacy policy in force at time of submission
                $t->string('privacy_policy_version', 20)->nullable()->after('consent_given_at');

                // Soft-deletes for data-erasure ("right to be forgotten") support
                $t->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'contact_submissions',
            'question_submissions',
            'consulting_submissions',
            'comments',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropSoftDeletes();
                $t->dropColumn(['gdpr_consent', 'consent_given_at', 'privacy_policy_version']);
            });
        }
    }
};
