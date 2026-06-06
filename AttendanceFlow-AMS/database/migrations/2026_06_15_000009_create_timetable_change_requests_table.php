<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetable_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->nullable()->constrained('academic_sessions')->cascadeOnDelete();
            $table->foreignId('teacher_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();

            $table->string('action', 16);                          // create | update | delete
            $table->json('proposed_data')->nullable();             // payload for create/update
            $table->json('previous_data')->nullable();             // snapshot for update/delete
            $table->text('reason')->nullable();                    // teacher's note

            $table->string('status', 16)->default('pending');     // pending | approved | rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_note')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_change_requests');
    }
};
