<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1 — widen to string temporarily so no truncation warning fires
        DB::statement("ALTER TABLE announcements MODIFY COLUMN `type` VARCHAR(50) NOT NULL DEFAULT 'general'");

        // Step 2 — map old values to new semantic values (safe even if table is empty)
        DB::table('announcements')->where('type', 'info')->update(['type' => 'general']);
        DB::table('announcements')->where('type', 'warning')->update(['type' => 'event']);
        DB::table('announcements')->where('type', 'success')->update(['type' => 'program_update']);
        DB::table('announcements')->where('type', 'danger')->update(['type' => 'emergency']);

        // Step 3 — apply the new enum
        DB::statement("ALTER TABLE announcements MODIFY COLUMN `type`
            ENUM('general','event','emergency','program_update')
            NOT NULL DEFAULT 'general'");

        // Step 4 — make title nullable
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE announcements MODIFY COLUMN `type` VARCHAR(50) NOT NULL DEFAULT 'info'");

        DB::table('announcements')->where('type', 'general')->update(['type' => 'info']);
        DB::table('announcements')->where('type', 'event')->update(['type' => 'warning']);
        DB::table('announcements')->where('type', 'program_update')->update(['type' => 'success']);
        DB::table('announcements')->where('type', 'emergency')->update(['type' => 'danger']);

        DB::statement("ALTER TABLE announcements MODIFY COLUMN `type`
            ENUM('info','warning','success','danger')
            NOT NULL DEFAULT 'info'");

        Schema::table('announcements', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
        });
    }
};
