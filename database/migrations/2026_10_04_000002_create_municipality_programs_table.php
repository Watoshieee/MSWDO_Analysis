<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipality_programs', function (Blueprint $table) {
            $table->id();
            $table->string('municipality_name', 100);
            $table->string('program_key', 100);
            $table->string('program_label', 200);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->unique(['municipality_name', 'program_key']);
            $table->index('municipality_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipality_programs');
    }
};
