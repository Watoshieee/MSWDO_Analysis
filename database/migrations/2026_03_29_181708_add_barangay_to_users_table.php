<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'barangay')) {
                $table->string('barangay')->nullable();
            }

            if (!Schema::hasColumn('users', 'mobile_number')) {
                $table->string('mobile_number', 20)->nullable();
            }

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            if (Schema::hasColumn('users', 'barangay')) {
                $table->dropColumn('barangay');
            }

            if (Schema::hasColumn('users', 'mobile_number')) {
                $table->dropColumn('mobile_number');
            }

        });
    }
};