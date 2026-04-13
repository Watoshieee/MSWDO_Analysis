<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("
                ALTER TABLE applications MODIFY COLUMN program_type ENUM(
                    '4Ps',
                    'Senior_Citizen_Pension',
                    'PWD_Assistance',
                    'AICS',
                    'AICS_Medical',
                    'AICS_Burial',
                    'SLP',
                    'ESA',
                    'Solo_Parent'
                ) NOT NULL
            ");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("
                ALTER TABLE applications MODIFY COLUMN program_type VARCHAR(255)
            ");
        }
    }
};