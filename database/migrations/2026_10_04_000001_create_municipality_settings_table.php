<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipality_settings', function (Blueprint $table) {
            $table->id();
            $table->string('municipality_name', 100)->unique();
            $table->string('display_name', 200)->nullable();
            $table->string('logo_path', 500)->nullable();
            $table->string('contact_email', 200)->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->text('office_address')->nullable();
            $table->string('office_hours', 200)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipality_settings');
    }
};
