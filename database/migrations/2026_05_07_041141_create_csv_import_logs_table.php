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
        Schema::create('csv_import_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('file_name');
            $table->string('file_type'); // 'municipality_data', 'barangay_data', 'program_data'
            $table->integer('total_rows');
            $table->integer('successful_rows')->default(0);
            $table->integer('failed_rows')->default(0);
            $table->text('error_details')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csv_import_logs');
    }
};
