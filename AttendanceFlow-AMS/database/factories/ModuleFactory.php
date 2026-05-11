<?php

namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Module>
 */
class ModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Module ' . $this->faker->words(2, true),
            'code' => $this->faker->unique()->lexify('MOD-???'),
            'coefficient' => $this->faker->numberBetween(1, 5),
        ];
    }
}
