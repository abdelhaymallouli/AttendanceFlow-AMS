<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->foreign('device_fingerprint_id', 'attendance_records_device_fingerprint_id_fk')
                ->references('id')->on('device_fingerprints')
                ->nullOnDelete();

            $table->foreign('qr_token_id', 'attendance_records_qr_token_id_fk')
                ->references('id')->on('qr_attendance_tokens')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropForeign('attendance_records_device_fingerprint_id_fk');
            $table->dropForeign('attendance_records_qr_token_id_fk');
        });
    }
};
