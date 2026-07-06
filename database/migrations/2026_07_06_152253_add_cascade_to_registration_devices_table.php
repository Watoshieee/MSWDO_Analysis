<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Clean up any existing orphaned records
        DB::statement('
            DELETE FROM registration_devices
            WHERE user_id NOT IN (SELECT id FROM users)
        ');
    }

    public function down(): void {}
};
