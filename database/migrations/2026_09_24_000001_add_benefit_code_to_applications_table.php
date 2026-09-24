<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('applications') && !Schema::hasColumn('applications', 'benefit_code')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->string('benefit_code', 20)->nullable()->after('category_code')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('applications') && Schema::hasColumn('applications', 'benefit_code')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('benefit_code');
            });
        }
    }
};