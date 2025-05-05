<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inspection>
 */
class InspectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'location' => $this->faker->address(),
            'attended_by' => User::inRandomOrder()->first()->id,
            'datetime' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'day_period' => rand(0, 3),
            'no_deficiencies_found' => $this->faker->boolean(70), // 70% chance of no deficiencies
            'status' => $this->faker->randomElement([0, 1, 2]), // Pending, In Progress, Completed
        ];
    }
}
