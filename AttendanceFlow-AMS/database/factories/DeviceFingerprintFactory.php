<?php

namespace Database\Factories;

use App\Models\DeviceFingerprint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceFingerprint>
 */
class DeviceFingerprintFactory extends Factory
{
    protected $model = DeviceFingerprint::class;

    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'fingerprint_hash' => hash('sha256', $this->faker->unique()->uuid()),
            'user_agent'       => $this->faker->userAgent(),
            'first_seen_at'    => now(),
            'last_seen_at'     => now(),
            'trust_score'      => 80,
        ];
    }

    public function revoked(): static
    {
        return $this->state(fn () => [
            'revoked_at' => now(),
        ]);
    }
}
