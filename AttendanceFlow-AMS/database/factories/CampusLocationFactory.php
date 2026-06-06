<?php

namespace Database\Factories;

use App\Models\CampusLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CampusLocation>
 */
class CampusLocationFactory extends Factory
{
    protected $model = CampusLocation::class;

    public function definition(): array
    {
        return [
            'name'             => $this->faker->company() . ' Campus',
            'code'             => strtoupper($this->faker->unique()->lexify('????-???')),
            'latitude'         => 33.5731,
            'longitude'        => -7.5898,
            'radius_meters'    => 50,
            'allowed_subnets'  => ['192.168.10.0/24', '10.190.0.0/16'],
            'is_active'        => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
