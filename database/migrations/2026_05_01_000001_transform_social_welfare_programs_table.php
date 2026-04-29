<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check current table structure
        $columns = Schema::getColumnListing('social_welfare_programs');
        
        // If table has old structure (name, code, description)
        if (in_array('name', $columns) && in_array('code', $columns)) {
            // Drop old columns
            DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS name');
            DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS code');
            DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS description');
            DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS created_at');
            DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS updated_at');
        }
        
        // Add new columns if they don't exist
        if (!in_array('municipality', $columns)) {
            DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN municipality VARCHAR(255) NOT NULL AFTER id');
        }
        
        if (!in_array('barangay', $columns)) {
            DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN barangay VARCHAR(255) NULL AFTER municipality');
        }
        
        if (!in_array('program_type', $columns)) {
            DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN program_type VARCHAR(255) NOT NULL AFTER barangay');
        }
        
        if (!in_array('beneficiary_count', $columns)) {
            DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN beneficiary_count INT NOT NULL DEFAULT 0 AFTER program_type');
        }
        
        if (!in_array('year', $columns)) {
            DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN year INT NOT NULL AFTER beneficiary_count');
        }
        
        if (!in_array('month', $columns)) {
            DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN month INT NULL AFTER year');
        }
        
        // Add indexes
        $indexes = DB::select("SHOW INDEX FROM social_welfare_programs");
        $indexNames = array_column($indexes, 'Key_name');
        
        if (!in_array('social_welfare_programs_municipality_year_index', $indexNames)) {
            DB::statement('CREATE INDEX social_welfare_programs_municipality_year_index ON social_welfare_programs (municipality, year)');
        }
        
        if (!in_array('social_welfare_programs_program_type_index', $indexNames)) {
            DB::statement('CREATE INDEX social_welfare_programs_program_type_index ON social_welfare_programs (program_type)');
        }
    }

    public function down(): void
    {
        // Reverse the transformation
        DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS municipality');
        DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS barangay');
        DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS program_type');
        DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS beneficiary_count');
        DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS year');
        DB::statement('ALTER TABLE social_welfare_programs DROP COLUMN IF EXISTS month');
        
        // Add back old columns
        DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN name VARCHAR(255) NOT NULL AFTER id');
        DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN code VARCHAR(255) NOT NULL AFTER name');
        DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN description TEXT NULL AFTER code');
        DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN created_at TIMESTAMP NULL AFTER description');
        DB::statement('ALTER TABLE social_welfare_programs ADD COLUMN updated_at TIMESTAMP NULL AFTER created_at');
    }
};
