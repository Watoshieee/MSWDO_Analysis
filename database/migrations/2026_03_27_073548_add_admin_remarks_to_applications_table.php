<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('applications', 'admin_remarks')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->text('admin_remarks')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('admin_remarks');
        });
    }
};
