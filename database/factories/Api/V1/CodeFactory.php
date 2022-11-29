<?php

namespace Database\Factories\Api\V1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Api\V1\Code>
 */
class CodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $code = [];

        $numbersCount = $this->faker->numberBetween(1, 12);
        $numbersFrom = $this->faker->numberBetween(1, 24);
        $numbersTo = $this->faker->numberBetween(24, 48);

        for ($i = 0; $i < $numbersCount; $i++) {
            $code[] = $this->faker->numberBetween($numbersFrom, $numbersTo);
        }

        return [
            'code' => $code,
        ];
    }
}
