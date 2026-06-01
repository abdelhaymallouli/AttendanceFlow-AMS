<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('justifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('session_id')->nullable()->constrained('academic_sessions')->cascadeOnDelete();
            $table->text('reason');
            $table->string('document_name')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('attendance_records', function (Blueprint $table) {
            $table->foreignId('justification_id')->nullable()->constrained('justifications')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
public function down(): void
{
    Schema::table('attendance_records', function (Blueprint $table) {
        if (Schema::hasColumn('attendance_records', 'justification_id')) {
            // Safely remove FK + column (Laravel handles FK name internally)
            $table->dropForeignId('justification_id');
        }
    });

    Schema::dropIfExists('justifications');
}
};