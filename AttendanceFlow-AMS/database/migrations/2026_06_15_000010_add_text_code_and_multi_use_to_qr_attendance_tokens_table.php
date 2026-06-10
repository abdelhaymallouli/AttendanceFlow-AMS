<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_attendance_tokens', function (Blueprint $table) {
            $table->string('text_code', 16)->nullable()->after('token_hash');
            $table->boolean('is_multi_use')->default(true)->after('expires_at');

            $table->index('text_code');
        });
    }

    public function down(): void
    {
        Schema::table('qr_attendance_tokens', function (Blueprint $table) {
            $table->dropIndex(['text_code']);
            $table->dropColumn(['text_code', 'is_multi_use']);
        });
    }
};
