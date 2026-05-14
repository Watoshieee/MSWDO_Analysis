<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add database indexes to speed up the most common mobile API queries.
     * Also adds push_sent_at to notifications if not already present.
     */
    public function up(): void
    {
        // ── appointments indexes ─────────────────────────────────────────────
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                // getApplication, bookAppointment, notifications
                if (!$this->indexExists('appointments', 'idx_appt_user_program_status')) {
                    $table->index(['user_id', 'program_type', 'status'], 'idx_appt_user_program_status');
                }
                // slot count query
                if (!$this->indexExists('appointments', 'idx_appt_date_time_muni')) {
                    $table->index(['appointment_date', 'appointment_time', 'municipality'], 'idx_appt_date_time_muni');
                }
            });
        }

        // ── applications indexes ─────────────────────────────────────────────
        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                // eligibility checks, submitApplication
                if (!$this->indexExists('applications', 'idx_app_user_program_status')) {
                    $table->index(['user_id', 'program_type', 'status'], 'idx_app_user_program_status');
                }
                // ID status checks
                if (!$this->indexExists('applications', 'idx_app_user_id_status')) {
                    $table->index(['user_id', 'id_status'], 'idx_app_user_id_status');
                }
            });
        }

        // ── notifications indexes ─────────────────────────────────────────────
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                // getNotifications, push dispatcher
                if (!$this->indexExists('notifications', 'idx_notif_user_type')) {
                    $table->index(['user_id', 'type'], 'idx_notif_user_type');
                }
                // push dispatcher batch query
                if (!$this->indexExists('notifications', 'idx_notif_push_sent')) {
                    $table->index(['push_sent_at', 'created_at'], 'idx_notif_push_sent');
                }
            });

            // Add push_sent_at column if not exists (idempotent)
            if (!Schema::hasColumn('notifications', 'push_sent_at')) {
                Schema::table('notifications', function (Blueprint $table) {
                    $table->timestamp('push_sent_at')->nullable()->after('updated_at');
                });
            }
        }

        // ── device_tokens indexes ─────────────────────────────────────────────
        if (Schema::hasTable('device_tokens')) {
            Schema::table('device_tokens', function (Blueprint $table) {
                if (!$this->indexExists('device_tokens', 'idx_dt_user_active')) {
                    $table->index(['user_id', 'is_active'], 'idx_dt_user_active');
                }
            });
        }

        // ── file_uploads indexes ─────────────────────────────────────────────
        if (Schema::hasTable('file_uploads')) {
            Schema::table('file_uploads', function (Blueprint $table) {
                if (!$this->indexExists('file_uploads', 'idx_fu_status')) {
                    $table->index(['status', 'program_type'], 'idx_fu_status');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('appointments')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_appt_user_program_status');
                $table->dropIndexIfExists('idx_appt_date_time_muni');
            });
        }
        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_app_user_program_status');
                $table->dropIndexIfExists('idx_app_user_id_status');
            });
        }
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_notif_user_type');
                $table->dropIndexIfExists('idx_notif_push_sent');
            });
        }
        if (Schema::hasTable('device_tokens')) {
            Schema::table('device_tokens', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_dt_user_active');
            });
        }
        if (Schema::hasTable('file_uploads')) {
            Schema::table('file_uploads', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_fu_status');
            });
        }
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return !empty($indexes);
    }
};
