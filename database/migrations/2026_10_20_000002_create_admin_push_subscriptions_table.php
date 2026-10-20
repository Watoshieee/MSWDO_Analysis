<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Admin Push Subscriptions Table
     *
     * Stores Web Push API (VAPID) subscriptions for admin users.
     * Each admin browser instance has its own push subscription endpoint.
     * These subscriptions are what enable Windows desktop notifications
     * even when the browser is closed.
     */
    public function up(): void
    {
        Schema::create('admin_push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->index();
            $table->text('endpoint');                        // push service URL (browser-specific)
            $table->text('p256dh');                          // public key
            $table->text('auth');                            // auth secret
            $table->string('user_agent', 300)->nullable();   // browser identifier
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            // Unique per endpoint so we can upsert without duplicates
            $table->unique(['admin_id', 'endpoint'], 'admin_push_unique_endpoint');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_push_subscriptions');
    }
};
