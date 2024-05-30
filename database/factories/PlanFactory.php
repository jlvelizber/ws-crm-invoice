<?php

namespace Database\Factories;

use App\Enums\PlanTermType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'short_name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'active' => true,
            'term_number' => $this->faker->numberBetween(1, 12),
            'term_type_time' => $this->faker->randomElement(PlanTermType::class)
        ];
    }
}
