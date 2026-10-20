<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin Notifications Table
     *
     * Persistent notifications for admin users (separate from user-facing notifications).
     * Covers: new user registrations awaiting ID verification, new applications, renewals, etc.
     */
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->index(); // which admin receives this
            $table->string('type', 60)->index();             // 'new_user_registration', 'new_application', etc.
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable();                // extra payload (reference IDs, URL, etc.)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('push_sent_at')->nullable();   // web push dispatch marker
            $table->timestamps();

            $table->index(['admin_id', 'is_read']);
            $table->index(['admin_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
