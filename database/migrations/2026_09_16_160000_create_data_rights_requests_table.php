<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the data_rights_requests table.
     *
     * Stores Art. 15–22 GDPR data-subject rights requests submitted
     * through the public /{locale}/data-rights form.
     */
    public function up(): void
    {
        Schema::create('data_rights_requests', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Requester
            $table->string('email');

            // Which right they are exercising
            $table->enum('request_type', [
                'access',        // Art. 15 — right of access
                'rectification', // Art. 16 — right to rectification
                'erasure',       // Art. 17 — right to erasure ("right to be forgotten")
                'portability',   // Art. 20 — right to data portability
                'objection',     // Art. 21 — right to object
                'restriction',   // Art. 18 — right to restriction of processing
            ]);

            // Workflow status — admin updates manually
            $table->enum('status', ['pending', 'completed', 'rejected'])
                ->default('pending');

            // Secure token for the signed download / confirm-deletion link (emailed to requester)
            $table->string('token', 64)->unique();

            // Optional description from the requester
            $table->text('notes_requester')->nullable();

            // Admin internal notes
            $table->text('notes_admin')->nullable();

            // Locale used when submitting (for DPO reply language)
            $table->string('locale', 5)->default('fr');

            // IP address at time of request (stored for 90-day security log then anonymised)
            $table->string('ip_address', 45)->nullable();

            // Timestamp of admin action
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index for email lookups (admin searching by requester email)
            $table->index('email');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rights_requests');
    }
};
