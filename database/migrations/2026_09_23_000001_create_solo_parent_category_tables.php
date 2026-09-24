<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add category_code to applications table
        if (Schema::hasTable('applications') && !Schema::hasColumn('applications', 'category_code')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->string('category_code', 10)->nullable()->after('program_type')->index();
            });
        }

        // 2. Master category requirements (admin-editable)
        if (!Schema::hasTable('solo_parent_category_requirements')) {
            Schema::create('solo_parent_category_requirements', function (Blueprint $table) {
                $table->id();
                $table->string('category_code', 10)->index();
                $table->string('group_key', 100)->index();
                $table->string('group_title', 255);
                $table->text('requirement_name');
                $table->text('description')->nullable();
                $table->boolean('is_or_group')->default(false);
                $table->boolean('is_active')->default(true);
                $table->integer('order_num')->default(0);
                $table->timestamps();
            });
        }

        // 3. Application snapshot requirements (fixed per application when category is assigned)
        if (!Schema::hasTable('solo_parent_application_requirements')) {
            Schema::create('solo_parent_application_requirements', function (Blueprint $table) {
                $table->id();
                $table->integer('application_id')->index(); // int(11) signed — matches applications.id
                $table->string('category_code', 10)->index();
                $table->string('group_key', 100)->index();
                $table->string('group_title', 255);
                $table->text('requirement_name');
                $table->boolean('is_or_group')->default(false);
                $table->integer('order_num')->default(0);
                $table->timestamps();

                $table->foreign('application_id')
                      ->references('id')
                      ->on('applications')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('solo_parent_application_requirements');
        Schema::dropIfExists('solo_parent_category_requirements');
        if (Schema::hasTable('applications') && Schema::hasColumn('applications', 'category_code')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('category_code');
            });
        }
    }
};