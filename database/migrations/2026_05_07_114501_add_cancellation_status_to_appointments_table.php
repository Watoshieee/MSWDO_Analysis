<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->text('cancel_reason')->nullable()->after('user_notes');
            $table->enum('cancellation_status', ['pending', 'approved', 'rejected'])->nullable()->after('cancel_reason');
            $table->text('cancellation_admin_notes')->nullable()->after('cancellation_status');
            $table->timestamp('cancellation_requested_at')->nullable()->after('cancellation_admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['cancel_reason', 'cancellation_status', 'cancellation_admin_notes', 'cancellation_requested_at']);
        });
    }
};
