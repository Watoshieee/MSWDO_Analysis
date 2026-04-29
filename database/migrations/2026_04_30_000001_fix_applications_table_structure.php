<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration adds missing columns to the applications table
     * and removes the unused created_at/updated_at columns.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('applications', 'municipality')) {
                $table->string('municipality')->nullable()->after('program_type');
            }
            if (!Schema::hasColumn('applications', 'barangay')) {
                $table->string('barangay')->nullable()->after('municipality');
            }
            if (!Schema::hasColumn('applications', 'full_name')) {
                $table->string('full_name')->nullable()->after('barangay');
            }
            if (!Schema::hasColumn('applications', 'age')) {
                $table->integer('age')->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('applications', 'gender')) {
                $table->string('gender')->nullable()->after('age');
            }
            if (!Schema::hasColumn('applications', 'contact_number')) {
                $table->string('contact_number')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('applications', 'application_date')) {
                $table->timestamp('application_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('applications', 'year')) {
                $table->string('year')->nullable()->after('application_date');
            }
            if (!Schema::hasColumn('applications', 'proof_photo_path')) {
                $table->string('proof_photo_path')->nullable()->after('aics_subtype');
            }
            if (!Schema::hasColumn('applications', 'id_status')) {
                $table->string('id_status')->nullable()->after('proof_photo_path');
            }
            if (!Schema::hasColumn('applications', 'id_ready_at')) {
                $table->timestamp('id_ready_at')->nullable()->after('id_status');
            }
        });

        // Drop created_at and updated_at if they exist (in a separate statement)
        if (Schema::hasColumn('applications', 'created_at') || Schema::hasColumn('applications', 'updated_at')) {
            Schema::table('applications', function (Blueprint $table) {
                if (Schema::hasColumn('applications', 'created_at')) {
                    $table->dropColumn('created_at');
                }
                if (Schema::hasColumn('applications', 'updated_at')) {
                    $table->dropColumn('updated_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop the columns we added
            $columns = [
                'municipality',
                'barangay',
                'full_name',
                'age',
                'gender',
                'contact_number',
                'application_date',
                'year',
                'proof_photo_path',
                'id_status',
                'id_ready_at'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('applications', $column)) {
                    $table->dropColumn($column);
                }
            }

            // Add back timestamps
            $table->timestamps();
        });
    }
};
