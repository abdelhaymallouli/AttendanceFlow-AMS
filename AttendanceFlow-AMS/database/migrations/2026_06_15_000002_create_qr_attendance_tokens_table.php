<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_attendance_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('academic_sessions')->cascadeOnDelete();
            $table->string('token_hash', 128);
            $table->string('nonce', 64);
            $table->timestamp('issued_at');
            $table->timestamp('expires_at');
            $table->boolean('is_consumed')->default(false);
            $table->foreignId('consumed_by_student_id')->nullable()->constrained('student_profiles')->nullOnDelete();
            $table->timestamp('consumed_at')->nullable();
            $table->string('consumed_ip', 45)->nullable();
            $table->timestamps();

            $table->index(['session_id', 'expires_at']);
            $table->index(['token_hash']);
            $table->index(['is_consumed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_attendance_tokens');
    }
};
