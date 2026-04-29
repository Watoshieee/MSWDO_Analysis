<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // municipalities
        if (!Schema::hasTable('municipalities')) {
            Schema::create('municipalities', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->integer('total_households')->default(0);
                $table->integer('male_population')->default(0);
                $table->integer('female_population')->default(0);
                $table->integer('population_0_19')->default(0);
                $table->integer('population_20_59')->default(0);
                $table->integer('population_60_100')->default(0);
                $table->integer('single_parent_count')->default(0);
                $table->integer('year')->nullable();
                $table->timestamp('created_at')->nullable();
                $table->softDeletes();
            });
        }

        // municipality_yearly_summary
        if (!Schema::hasTable('municipality_yearly_summary')) {
            Schema::create('municipality_yearly_summary', function (Blueprint $table) {
                $table->id();
                $table->string('municipality');
                $table->integer('year');
                $table->integer('total_population')->default(0);
                $table->integer('total_households')->default(0);
                $table->integer('total_4ps')->default(0);
                $table->integer('total_pwd')->default(0);
                $table->integer('total_senior')->default(0);
                $table->integer('total_aics')->default(0);
                $table->integer('total_esa')->default(0);
                $table->integer('total_slp')->default(0);
                $table->integer('total_solo_parent')->default(0);
                $table->timestamp('created_at')->nullable();
                $table->softDeletes();
            });
        }

        // barangays
        if (!Schema::hasTable('barangays')) {
            Schema::create('barangays', function (Blueprint $table) {
                $table->id();
                $table->string('municipality');
                $table->string('name');
                $table->integer('male_population')->default(0);
                $table->integer('female_population')->default(0);
                $table->integer('population_0_19')->default(0);
                $table->integer('population_20_59')->default(0);
                $table->integer('population_60_100')->default(0);
                $table->integer('single_parent_count')->default(0);
                $table->integer('pwd_count')->default(0);
                $table->integer('aics_count')->default(0);
                $table->integer('total_households')->default(0);
                $table->integer('total_approved_applications')->default(0);
                $table->integer('year')->nullable();
                $table->unique(['municipality', 'name', 'year']);
            });
        }

        // social_welfare_programs
        if (!Schema::hasTable('social_welfare_programs')) {
            Schema::create('social_welfare_programs', function (Blueprint $table) {
                $table->id();
                $table->string('municipality');
                $table->string('barangay')->nullable();
                $table->string('program_type');
                $table->integer('beneficiary_count')->default(0);
                $table->integer('year');
                $table->softDeletes();
            });
        }

        // applications
        if (!Schema::hasTable('applications')) {
            Schema::create('applications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('program_type');
                $table->string('municipality')->nullable();
                $table->string('barangay')->nullable();
                $table->string('full_name')->nullable();
                $table->integer('age')->nullable();
                $table->string('gender')->nullable();
                $table->string('contact_number')->nullable();
                $table->string('status')->default('pending');
                $table->timestamp('application_date')->nullable();
                $table->integer('year')->nullable();
                $table->json('form_data')->nullable();
                $table->string('stage')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->string('proof_photo_path')->nullable();
                $table->string('id_status')->nullable();
                $table->timestamp('id_ready_at')->nullable();
                $table->text('admin_remarks')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // file_monitoring
        if (!Schema::hasTable('file_monitoring')) {
            Schema::create('file_monitoring', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('application_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('assigned_admin_id')->nullable();
                $table->string('priority')->default('normal');
                $table->string('overall_status')->default('pending');
                $table->text('notes')->nullable();
                $table->string('municipality')->nullable();
                $table->timestamps();
                $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            });
        }

        // file_uploads
        if (!Schema::hasTable('file_uploads')) {
            Schema::create('file_uploads', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('file_monitoring_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('file_name')->nullable();
                $table->string('file_path')->nullable();
                $table->string('requirement_name')->nullable();
                $table->string('status')->default('pending');
                $table->text('remarks')->nullable();
                $table->text('admin_remarks')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->unsignedBigInteger('verified_by')->nullable();
                $table->timestamp('uploaded_at')->nullable();
                $table->string('municipality')->nullable();
                $table->foreign('file_monitoring_id')->references('id')->on('file_monitoring')->onDelete('cascade');
            });
        }

        // file_status_logs
        if (!Schema::hasTable('file_status_logs')) {
            Schema::create('file_status_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('file_monitoring_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('changed_by')->nullable();
                $table->string('old_status')->nullable();
                $table->string('new_status')->nullable();
                $table->text('remarks')->nullable();
                $table->string('requirement_name')->nullable();
                $table->string('municipality')->nullable();
                $table->timestamp('created_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('file_status_logs');
        Schema::dropIfExists('file_uploads');
        Schema::dropIfExists('file_monitoring');
        Schema::dropIfExists('applications');
        Schema::dropIfExists('social_welfare_programs');
        Schema::dropIfExists('barangays');
        Schema::dropIfExists('municipality_yearly_summary');
        Schema::dropIfExists('municipalities');
    }
};
