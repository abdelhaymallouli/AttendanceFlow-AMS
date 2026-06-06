<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Structured payload: redirect URL, entity IDs, context for the popup
            $table->json('data')->nullable()->after('type');
            // Category for grouping: absence, present, justification.*, session.*, etc.
            $table->string('category', 64)->default('general')->after('data')->index();
            // Audience: 'student', 'teacher', 'admin'
            $table->string('audience', 32)->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['data', 'category', 'audience']);
        });
    }
};
