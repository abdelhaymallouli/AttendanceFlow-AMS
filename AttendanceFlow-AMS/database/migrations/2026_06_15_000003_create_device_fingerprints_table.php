<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_fingerprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('fingerprint_hash', 64);
            $table->string('user_agent', 512)->nullable();
            $table->timestamp('first_seen_at');
            $table->timestamp('last_seen_at');
            $table->unsignedTinyInteger('trust_score')->default(50);
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'fingerprint_hash']);
            $table->index('fingerprint_hash');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_fingerprints');
    }
};
