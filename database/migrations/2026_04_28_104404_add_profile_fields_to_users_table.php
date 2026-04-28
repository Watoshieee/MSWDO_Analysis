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
        Schema::table('users', function (Blueprint $table) {
            // Add new profile fields if they don't exist
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name', 100)->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name', 100)->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('users', 'middle_name')) {
                $table->string('middle_name', 100)->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 20)->nullable()->after('mobile_number');
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('birthdate');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('barangay');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender', 20)->nullable()->after('age');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'middle_name', 'phone_number', 'date_of_birth', 'address', 'gender']);
        });
    }
};
