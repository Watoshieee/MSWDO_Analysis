<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->date('reschedule_date')->nullable()->after('admin_notes');
            $table->string('reschedule_time')->nullable()->after('reschedule_date');
            $table->text('reschedule_reason')->nullable()->after('reschedule_time');
            $table->enum('reschedule_status', ['pending', 'approved', 'rejected'])->nullable()->after('reschedule_reason');
            $table->timestamp('reschedule_requested_at')->nullable()->after('reschedule_status');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['reschedule_date', 'reschedule_time', 'reschedule_reason', 'reschedule_status', 'reschedule_requested_at']);
        });
    }
};
