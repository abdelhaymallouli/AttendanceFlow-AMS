<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\Session;
use App\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    protected $model = AttendanceRecord::class;

    public function definition(): array
    {
        return [
            'student_profile_id' => StudentProfile::factory(),
            'session_id'         => Session::factory(),
            'status'             => $this->faker->randomElement(['present', 'absent', 'late', 'justified']),
            'date'               => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
        ];
    }
}
