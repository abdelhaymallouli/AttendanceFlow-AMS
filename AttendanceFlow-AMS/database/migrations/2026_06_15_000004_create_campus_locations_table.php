<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campus_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->unsignedInteger('radius_meters')->default(50);
            $table->json('allowed_subnets')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });

        // Seed default Solicode campus (Casablanca)
        DB::table('campus_locations')->insert([
            'name' => 'Solicode Casablanca',
            'code' => 'CASA-SOLI',
            'latitude' => 33.5731,
            'longitude' => -7.5898,
            'radius_meters' => 50,
            'allowed_subnets' => json_encode(['192.168.10.0/24', '10.190.0.0/16']),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('campus_locations');
    }
};
