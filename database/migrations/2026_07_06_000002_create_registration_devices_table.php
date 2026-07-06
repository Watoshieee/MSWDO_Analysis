<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('registration_devices')) {
            Schema::create('registration_devices', function (Blueprint $table) {
                $table->id();
                $table->string('device_fingerprint', 64)->index();
                $table->unsignedBigInteger('user_id')->unique();
                $table->timestamp('registered_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_devices');
    }
};
