<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ensures the applications table has id_status and id_ready_at columns
 * needed for Solo Parent ID issuance tracking.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'id_status')) {
                $table->string('id_status')->nullable()->after('proof_photo_path')
                    ->comment('Tracks ID readiness: null | ready_for_pickup | delivered');
            }
            if (!Schema::hasColumn('applications', 'id_ready_at')) {
                $table->timestamp('id_ready_at')->nullable()->after('id_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'id_ready_at')) {
                $table->dropColumn('id_ready_at');
            }
            if (Schema::hasColumn('applications', 'id_status')) {
                $table->dropColumn('id_status');
            }
        });
    }
};
