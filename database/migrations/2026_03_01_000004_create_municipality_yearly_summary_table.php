<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipality_yearly_summary', function (Blueprint $table) {
            $table->id();
            $table->string('municipality');
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('total_pwd')->default(0);
            $table->unsignedInteger('total_aics')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Unique constraint
            $table->unique(['municipality', 'year'], 'yearly_summary_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipality_yearly_summary');
    }
};