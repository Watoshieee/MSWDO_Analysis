<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Allow gender to be NULL — Solo Parent applications created from appointment
        // validation don't have gender data from the appointment booking form.
        DB::statement("ALTER TABLE applications MODIFY COLUMN gender ENUM('Male','Female') NULL DEFAULT NULL");
    }

    public function down(): void
    {
        // Revert to NOT NULL with a safe default
        DB::statement("UPDATE applications SET gender = 'Male' WHERE gender IS NULL");
        DB::statement("ALTER TABLE applications MODIFY COLUMN gender ENUM('Male','Female') NOT NULL");
    }
};
