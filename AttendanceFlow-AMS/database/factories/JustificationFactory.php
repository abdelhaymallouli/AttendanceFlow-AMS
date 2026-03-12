<?php

namespace Database\Factories;

use App\Models\Justification;
use App\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Justification>
 */
class JustificationFactory extends Factory
{
    protected $model = Justification::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-2 weeks', '-1 week');
        $end   = (clone $start)->modify('+2 days');

        return [
            'student_profile_id' => StudentProfile::factory(),
            'reason'             => $this->faker->sentence(),
            'file_path'          => null,
            'start_date'         => $start->format('Y-m-d'),
            'end_date'           => $end->format('Y-m-d'),
            'status'             => 'pending',
            'submitted_at'       => now(),
        ];
    }
}
