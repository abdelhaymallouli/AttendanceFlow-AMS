<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->string('check_in_method', 16)->default('manual')->after('status');
            $table->decimal('latitude', 10, 7)->nullable()->after('check_in_method');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->unsignedInteger('distance_meters')->nullable()->after('longitude');
            $table->string('wifi_ip', 45)->nullable()->after('distance_meters');
            $table->unsignedBigInteger('device_fingerprint_id')->nullable()->after('wifi_ip');
            $table->unsignedBigInteger('qr_token_id')->nullable()->after('device_fingerprint_id');
            $table->timestamp('synced_at')->nullable()->after('qr_token_id');
            $table->unsignedSmallInteger('validation_score')->nullable()->after('synced_at');
            $table->string('rejection_reason', 255)->nullable()->after('validation_score');

            $table->index(['session_id', 'check_in_method']);
            $table->index('synced_at');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropIndex(['session_id', 'check_in_method']);
            $table->dropIndex(['synced_at']);

            $table->dropColumn([
                'check_in_method',
                'latitude',
                'longitude',
                'distance_meters',
                'wifi_ip',
                'device_fingerprint_id',
                'qr_token_id',
                'synced_at',
                'validation_score',
                'rejection_reason',
            ]);
        });
    }
};
