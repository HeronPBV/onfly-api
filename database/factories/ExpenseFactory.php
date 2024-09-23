<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence(),
            'date' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'user_id' => User::all()->random()->id,
            'value' => $this->faker->numberBetween(100, 10000)
        ];
    }
}
