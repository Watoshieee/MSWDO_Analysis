<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipalities', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('code')->unique();
    $table->integer('total_households')->default(0);
    $table->integer('male_population')->default(0);
    $table->integer('female_population')->default(0);
    $table->integer('population_0_19')->default(0);
    $table->integer('population_20_59')->default(0);
    $table->integer('population_51_100')->default(0);
    $table->integer('single_parent_count')->default(0);
    $table->integer('year')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};