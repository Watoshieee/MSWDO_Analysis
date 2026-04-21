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
        Schema::create('admin_municipality_data', function (Blueprint $table) {
            $table->id();
            $table->string('municipality');
            $table->integer('year');
            $table->integer('total_population')->default(0);
            $table->integer('total_households')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_municipality_data');
    }
};
