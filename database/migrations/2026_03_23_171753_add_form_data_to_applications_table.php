<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('applications', 'form_data')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->json('form_data')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('form_data');
        });
    }
};