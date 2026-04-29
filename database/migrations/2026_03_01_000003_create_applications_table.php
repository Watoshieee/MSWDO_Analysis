<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('program_type');
            $table->string('municipality')->nullable();
            $table->string('barangay')->nullable();
            $table->string('full_name')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('application_date')->nullable();
            $table->string('year')->nullable();
            $table->json('form_data')->nullable();
            $table->string('stage')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('admin_remarks')->nullable();
            $table->string('aics_subtype')->nullable();
            $table->string('proof_photo_path')->nullable();
            $table->string('id_status')->nullable();
            $table->timestamp('id_ready_at')->nullable();
            // Note: No created_at/updated_at - using application_date instead
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};