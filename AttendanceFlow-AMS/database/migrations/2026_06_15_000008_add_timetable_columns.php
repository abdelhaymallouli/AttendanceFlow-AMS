<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->float('total_hours')->default(30.0)->after('coefficient')
                ->comment('Total hours allocated to this module per year. Sum of session durations cannot exceed this.');
        });

        Schema::table('academic_sessions', function (Blueprint $table) {
            $table->boolean('is_published')->default(true)->after('type')
                ->comment('When true, the session is visible to students in their timetable.');
            $table->string('room')->nullable()->after('is_published');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('academic_sessions', function (Blueprint $table) {
            $table->dropIndex(['is_published']);
            $table->dropColumn(['is_published', 'room']);
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('total_hours');
        });
    }
};
