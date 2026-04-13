<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormDataToApplicationsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('applications') && !Schema::hasColumn('applications', 'form_data')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->json('form_data')->nullable()->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('applications') && Schema::hasColumn('applications', 'form_data')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('form_data');
            });
        }
    }
}