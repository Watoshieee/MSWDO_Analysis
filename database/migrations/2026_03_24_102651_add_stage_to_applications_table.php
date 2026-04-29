<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'stage')) {
                $table->string('stage')->nullable();
            }
            if (!Schema::hasColumn('applications', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['stage', 'completed_at']);
        });
    }
};