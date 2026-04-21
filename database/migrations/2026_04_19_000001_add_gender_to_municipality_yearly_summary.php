<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('municipality_yearly_summary', function (Blueprint $table) {
            $table->unsignedInteger('male_population')->default(0)->after('total_population');
            $table->unsignedInteger('female_population')->default(0)->after('male_population');
        });
    }

    public function down(): void
    {
        Schema::table('municipality_yearly_summary', function (Blueprint $table) {
            $table->dropColumn(['male_population', 'female_population']);
        });
    }
};
