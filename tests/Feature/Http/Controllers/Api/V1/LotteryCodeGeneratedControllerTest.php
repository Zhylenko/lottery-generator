<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LotteryCodeGeneratedControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_generate_lottery_code()
    {
        $lottery = Lottery::has('codes')->inRandomOrder()->first();

        $oldestLotteryCode = $lottery->codes()->oldest('id')->first();
        $latestLotteryCode = $lottery->codes()->latest('id')->first();

        $data = [
            'lottery' => $lottery->id,
            'exclude_code_combinations' => [],
            'include_sets_of_consecutive_2_numbers_combinations' => 1,
            'include_special_codes_combinations' => 0,
            'exclude_sets_of_consecutive_numbers_combinations' => 1,
            'exclude_generated_sets_of_consecutive_numbers_combinations' => 1,
            'count' => 1,
        ];

        for ($i = 1; $i <= $lottery->numbers_count; $i++) {
            $data['exclude_code_combinations'][] = [
                'numbers' => $i,
                'count' => $i * 10,
                'from' => $oldestLotteryCode->id,
                'to' => $latestLotteryCode->id,
            ];
        }

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(200)
            ->assertExactJson([1]);
    }
}
