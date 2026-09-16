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
            Schema::table($table, function (Blueprint $t) use ($table) {
                if (! Schema::hasColumn($table, 'gdpr_consent')) {
                    $col = $t->boolean('gdpr_consent')->default(false);
                    if (Schema::hasColumn($table, 'user_agent')) {
                        $col->after('user_agent');
                    }
                }

                if (! Schema::hasColumn($table, 'consent_given_at')) {
                    $t->timestamp('consent_given_at')->nullable()->after('gdpr_consent');
                }

                if (! Schema::hasColumn($table, 'privacy_policy_version')) {
                    $t->string('privacy_policy_version', 20)->nullable()->after('consent_given_at');
                }

                if (! Schema::hasColumn($table, 'deleted_at')) {
                    $t->softDeletes();
                }
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
