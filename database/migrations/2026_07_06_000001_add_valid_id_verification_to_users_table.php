<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'valid_id_path')) {
                $table->string('valid_id_path')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'valid_id_filename')) {
                $table->string('valid_id_filename')->nullable()->after('valid_id_path');
            }
            if (!Schema::hasColumn('users', 'id_verification_status')) {
                $table->string('id_verification_status', 20)->nullable()->after('valid_id_filename');
            }
            if (!Schema::hasColumn('users', 'id_verified_at')) {
                $table->timestamp('id_verified_at')->nullable()->after('id_verification_status');
            }
            if (!Schema::hasColumn('users', 'id_verified_by')) {
                $table->unsignedBigInteger('id_verified_by')->nullable()->after('id_verified_at');
            }
            if (!Schema::hasColumn('users', 'id_rejection_reason')) {
                $table->text('id_rejection_reason')->nullable()->after('id_verified_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'valid_id_path',
                'valid_id_filename',
                'id_verification_status',
                'id_verified_at',
                'id_verified_by',
                'id_rejection_reason',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
