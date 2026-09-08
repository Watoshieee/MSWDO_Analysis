<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('registration_token', 64)->nullable()->after('reset_token_expires_at');
            $table->timestamp('registration_token_expires_at')->nullable()->after('registration_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['registration_token', 'registration_token_expires_at']);
        });
    }
};
