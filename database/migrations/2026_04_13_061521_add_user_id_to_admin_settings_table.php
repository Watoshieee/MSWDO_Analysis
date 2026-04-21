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
        Schema::table('admin_settings', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->after('id')->nullable();
            $table->dropUnique(['setting_key']);
            $table->unique(['user_id', 'setting_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_settings', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'setting_key']);
            $table->dropColumn('user_id');
            $table->unique('setting_key');
        });
    }
};
