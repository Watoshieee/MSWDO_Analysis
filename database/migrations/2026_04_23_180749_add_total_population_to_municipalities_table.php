<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('municipalities', function (Blueprint $table) {
            if (!Schema::hasColumn('municipalities', 'total_population')) {
                $table->unsignedBigInteger('total_population')->default(0)->after('total_households');
            }
        });

        // Backfill total_population from male + female where it's 0
        \DB::statement('UPDATE municipalities SET total_population = male_population + female_population WHERE total_population = 0 AND (male_population > 0 OR female_population > 0)');
    }

    public function down(): void
    {
        Schema::table('municipalities', function (Blueprint $table) {
            if (Schema::hasColumn('municipalities', 'total_population')) {
                $table->dropColumn('total_population');
            }
        });
    }
};
