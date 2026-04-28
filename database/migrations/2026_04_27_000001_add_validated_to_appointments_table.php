<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: modify the ENUM to include 'validated'
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending','confirmed','validated','rejected','cancelled') NOT NULL DEFAULT 'pending'");

        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('solo_parent_app_id')->nullable()->after('status')
                  ->comment('FK to applications.id — set when admin validates the appointment');
            $table->timestamp('validated_at')->nullable()->after('solo_parent_app_id');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['solo_parent_app_id', 'validated_at']);
        });

        DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending','confirmed','rejected','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
