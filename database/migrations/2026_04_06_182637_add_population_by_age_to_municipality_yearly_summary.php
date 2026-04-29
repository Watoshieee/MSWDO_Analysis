<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('municipality_yearly_summary', function (Blueprint $table) {
            // Add total_population column if it doesn't exist
            if (!Schema::hasColumn('municipality_yearly_summary', 'total_population')) {
                $table->unsignedInteger('total_population')->default(0)->after('total_aics');
            }
            
            // Add age-based population columns
            if (!Schema::hasColumn('municipality_yearly_summary', 'population_0_19')) {
                $table->unsignedInteger('population_0_19')->default(0)->after('total_population');
            }
            if (!Schema::hasColumn('municipality_yearly_summary', 'population_20_59')) {
                $table->unsignedInteger('population_20_59')->default(0)->after('population_0_19');
            }
            if (!Schema::hasColumn('municipality_yearly_summary', 'population_60_100')) {
                $table->unsignedInteger('population_60_100')->default(0)->after('population_20_59');
            }
        });
    }

    public function down(): void
    {
        Schema::table('municipality_yearly_summary', function (Blueprint $table) {
            $table->dropColumn(['population_0_19', 'population_20_59', 'population_60_100']);
        });
    }
};
