<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barangays', function (Blueprint $table) {
            $table->integer('four_ps_count')->default(0)->nullable()->after('aics_count');
            $table->integer('senior_count')->default(0)->nullable()->after('four_ps_count');
        });
    }

    public function down(): void
    {
        Schema::table('barangays', function (Blueprint $table) {
            $table->dropColumn(['four_ps_count', 'senior_count']);
        });
    }
};
