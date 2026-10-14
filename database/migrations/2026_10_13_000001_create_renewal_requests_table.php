<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('renewal_requests')) {
            Schema::create('renewal_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->string('municipality', 50);
                $table->string('program_type', 50); // 'PWD' or 'Solo_Parent'
                $table->string('id_file_path', 255);
                $table->string('id_file_name', 255)->nullable();
                $table->unsignedBigInteger('appointment_id')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
                $table->text('user_notes')->nullable();
                $table->text('admin_remarks')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['user_id', 'program_type', 'status']);
                $table->index(['municipality', 'program_type', 'status']);
                $table->index('appointment_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renewal_requests');
    }
};
