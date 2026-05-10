<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Module;
use App\Models\Session;
use App\Models\TeacherProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Session>
 */
class SessionFactory extends Factory
{
    protected $model = Session::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 week', '+2 weeks');
        $end   = (clone $start)->modify('+2 hours');

        return [
            'module_id'          => Module::factory(),
            'teacher_profile_id' => TeacherProfile::factory(),
            'group_id'           => Group::factory(),
            'start_time'         => $start,
            'end_time'           => $end,
            'duration_hours'     => 2.0,
            'type'               => $this->faker->randomElement(['CM', 'TD', 'TP']),
        ];
    }

    /**
     * Indicate that the session is happening right now.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_time' => now()->subHour(),
            'end_time'   => now()->addHours(2),
            'duration_hours' => 3.0,
        ]);
    }
}
