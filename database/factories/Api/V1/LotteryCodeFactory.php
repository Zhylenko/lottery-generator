<?php

namespace Database\Factories\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Api\V1\LotteryCode>
 */
class LotteryCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $lottery = Lottery::all()->random();
        $code = [];

        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $code[] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        return [
            'code' => $code,
            'lottery_id' => $lottery->id,
        ];
    }
}
