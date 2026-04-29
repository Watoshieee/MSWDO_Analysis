<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipality_visions', function (Blueprint $table) {
            $table->id();
            $table->string('municipality_name')->unique();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('goals')->nullable();
            $table->json('strategic_goals')->nullable(); // JSON array of strategic goal strings
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipality_visions');
    }
};
