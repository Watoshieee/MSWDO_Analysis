<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipality_monthly_summary', function (Blueprint $table) {
            $table->id();
            $table->string('municipality');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month'); // 1-12
            $table->unsignedInteger('total_pwd')->default(0);
            $table->unsignedInteger('total_aics')->default(0);
            $table->unsignedInteger('total_solo_parent')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Unique: one record per municipality per year-month
            $table->unique(['municipality', 'year', 'month'], 'monthly_unique');
            $table->index(['municipality', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipality_monthly_summary');
    }
};
