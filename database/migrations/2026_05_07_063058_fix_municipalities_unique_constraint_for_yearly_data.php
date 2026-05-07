<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop the unique constraint on 'name' column using raw SQL
        DB::statement('ALTER TABLE municipalities DROP INDEX `name`');
        
        // Add composite unique constraint on 'name' + 'year'
        Schema::table('municipalities', function (Blueprint $table) {
            $table->unique(['name', 'year'], 'municipalities_name_year_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop composite unique constraint
        Schema::table('municipalities', function (Blueprint $table) {
            $table->dropUnique('municipalities_name_year_unique');
        });
        
        // Restore unique constraint on 'name' only
        Schema::table('municipalities', function (Blueprint $table) {
            $table->unique('name');
        });
    }
};
