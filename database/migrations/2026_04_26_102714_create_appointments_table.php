<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->index();
            $table->string('municipality');
            $table->date('appointment_date');
            $table->string('appointment_time'); // e.g. "08:00", "09:00" … "16:00"
            $table->enum('interview_type', ['face_to_face', 'online'])->default('face_to_face');
            $table->string('program_type')->default('Solo_Parent');
            $table->enum('status', ['pending', 'confirmed', 'rejected', 'cancelled'])->default('pending');
            $table->text('user_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('reminded_at')->nullable(); // set when tomorrow-reminder email sent
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
