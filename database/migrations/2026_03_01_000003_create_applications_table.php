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
            $table->string('status')->default('pending');
            $table->json('form_data')->nullable();
            $table->string('stage')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('admin_remarks')->nullable();
            $table->string('aics_subtype')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};