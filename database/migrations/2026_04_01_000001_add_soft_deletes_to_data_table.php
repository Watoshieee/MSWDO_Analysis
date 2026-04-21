<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('municipalities', 'deleted_at')) {
            Schema::table('municipalities', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
        if (!Schema::hasColumn('municipality_yearly_summary', 'deleted_at')) {
            Schema::table('municipality_yearly_summary', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
        if (!Schema::hasColumn('social_welfare_programs', 'deleted_at')) {
            Schema::table('social_welfare_programs', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::table('municipalities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('municipality_yearly_summary', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('social_welfare_programs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
