<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration fixes the social_welfare_programs table structure
     * to match what the model expects.
     */
    public function up(): void
    {
        // Check if the table has the old structure (name, code, description)
        if (Schema::hasColumn('social_welfare_programs', 'name') && 
            Schema::hasColumn('social_welfare_programs', 'code')) {
            
            // Drop the old columns
            Schema::table('social_welfare_programs', function (Blueprint $table) {
                $table->dropColumn(['name', 'code', 'description']);
                if (Schema::hasColumn('social_welfare_programs', 'created_at')) {
                    $table->dropColumn(['created_at', 'updated_at']);
                }
            });
        }

        // Add the correct columns if they don't exist
        Schema::table('social_welfare_programs', function (Blueprint $table) {
            if (!Schema::hasColumn('social_welfare_programs', 'municipality')) {
                $table->string('municipality')->after('id');
            }
            if (!Schema::hasColumn('social_welfare_programs', 'barangay')) {
                $table->string('barangay')->nullable()->after('municipality');
            }
            if (!Schema::hasColumn('social_welfare_programs', 'program_type')) {
                $table->string('program_type')->after('barangay');
            }
            if (!Schema::hasColumn('social_welfare_programs', 'beneficiary_count')) {
                $table->integer('beneficiary_count')->default(0)->after('program_type');
            }
            if (!Schema::hasColumn('social_welfare_programs', 'year')) {
                $table->integer('year')->after('beneficiary_count');
            }
            if (!Schema::hasColumn('social_welfare_programs', 'month')) {
                $table->integer('month')->nullable()->after('year');
            }
        });

        // Add indexes for better performance using raw SQL
        $connection = Schema::getConnection();
        $indexes = $connection->select("SHOW INDEX FROM social_welfare_programs");
        $indexNames = array_column($indexes, 'Key_name');
        
        if (!in_array('social_welfare_programs_municipality_year_index', $indexNames)) {
            $connection->statement('CREATE INDEX social_welfare_programs_municipality_year_index ON social_welfare_programs (municipality, year)');
        }
        if (!in_array('social_welfare_programs_program_type_index', $indexNames)) {
            $connection->statement('CREATE INDEX social_welfare_programs_program_type_index ON social_welfare_programs (program_type)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_welfare_programs', function (Blueprint $table) {
            // Drop the new columns
            $columns = [
                'municipality',
                'barangay',
                'program_type',
                'beneficiary_count',
                'year',
                'month'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('social_welfare_programs', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Add back the old columns
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
};
