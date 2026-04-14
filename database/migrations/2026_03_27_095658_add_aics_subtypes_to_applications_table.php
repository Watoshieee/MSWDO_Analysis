<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check kung SQLite ang gamit
        if (DB::connection()->getDriverName() === 'sqlite') {
            // Sa SQLite, hindi kailangan ang ENUM modification
            // Gawin na lang nothing or mag-add ng check
            return;
        }
        
        // Original MySQL code
        DB::statement("ALTER TABLE applications MODIFY COLUMN program_type ENUM(
            '4Ps',
            'Senior_Citizen_Pension',
            'PWD_Assistance',
            'AICS',
            'AICS_Medical',
            'AICS_Burial',
            'SLP',
            'ESA',
            'Solo_Parent'
        ) NOT NULL");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }
        
        DB::statement("ALTER TABLE applications MODIFY COLUMN program_type ENUM(
            '4Ps',
            'Senior_Citizen_Pension',
            'PWD_Assistance',
            'AICS',
            'SLP',
            'ESA',
            'Solo_Parent'
        ) NOT NULL");
    }
};