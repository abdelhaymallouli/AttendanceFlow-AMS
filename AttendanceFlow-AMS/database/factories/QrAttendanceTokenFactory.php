<?php

namespace Database\Factories;

use App\Models\QrAttendanceToken;
use App\Models\Session;
use App\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QrAttendanceToken>
 */
class QrAttendanceTokenFactory extends Factory
{
    protected $model = QrAttendanceToken::class;

    public function definition(): array
    {
        $issuedAt = now();
        $expiresAt = (clone $issuedAt)->addSeconds(30);

        return [
            'session_id'  => Session::factory(),
            'token_hash'  => hash('sha256', $this->faker->unique()->uuid()),
            'nonce'       => bin2hex(random_bytes(16)),
            'issued_at'   => $issuedAt,
            'expires_at'  => $expiresAt,
            'is_consumed' => false,
            'consumed_by_student_id' => null,
            'consumed_at' => null,
            'consumed_ip' => null,
        ];
    }

    public function consumed(?StudentProfile $student = null): static
    {
        return $this->state(fn () => [
            'is_consumed' => true,
            'consumed_by_student_id' => $student?->id ?? StudentProfile::factory(),
            'consumed_at' => now(),
            'consumed_ip' => '127.0.0.1',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'issued_at' => now()->subMinutes(2),
            'expires_at' => now()->subMinutes(1),
        ]);
    }
}
