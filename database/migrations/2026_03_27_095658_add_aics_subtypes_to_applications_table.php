<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
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
