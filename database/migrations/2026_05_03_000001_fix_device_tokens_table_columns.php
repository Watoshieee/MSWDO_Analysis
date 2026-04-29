<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Corrective migration: ensures device_tokens has all required columns.
 *
 * There are two earlier device_tokens migrations (2026_04_28_201034 and 2026_04_28_203157).
 * If the first (minimal) one ran and the second was skipped due to table-already-exists,
 * this migration adds the missing columns safely.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('device_tokens')) {
            return; // Nothing to fix — the table will be created by the original migration
        }

        Schema::table('device_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('device_tokens', 'device_type')) {
                $table->string('device_type')->nullable()->after('token');
            }
            if (!Schema::hasColumn('device_tokens', 'device_name')) {
                $table->string('device_name')->nullable()->after('device_type');
            }
            if (!Schema::hasColumn('device_tokens', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('device_name');
            }
            if (!Schema::hasColumn('device_tokens', 'last_used_at')) {
                $table->timestamp('last_used_at')->nullable()->after('is_active');
            }
        });
    }

    public function down(): void
    {
        // Do not drop columns — this is a corrective migration only
    }
};
