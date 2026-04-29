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
        Schema::table('social_welfare_programs', function (Blueprint $table) {
            // Month column is now in the main create_social_welfare_programs_table migration
            // This migration is kept for compatibility but does nothing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_welfare_programs', function (Blueprint $table) {
            // No action needed
        });
    }
};
