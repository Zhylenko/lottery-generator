<?php

namespace Database\Factories\Api\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Api\V1\Lottery>
 */
class LotteryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique->words(3, true),
            'numbers_count' => $this->faker->numberBetween(1, 12),
            'numbers_from' => $this->faker->numberBetween(1, 24),
            'numbers_to' => $this->faker->numberBetween(24, 48),
        ];
    }
}
