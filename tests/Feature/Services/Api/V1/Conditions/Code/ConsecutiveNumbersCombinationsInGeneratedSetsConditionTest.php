<?php

namespace Tests\Feature\Services\Api\V1\Conditions\Code;

use App\Contracts\Api\V1\Condition;
use App\Models\Api\V1\Lottery;
use App\Services\Api\V1\Conditions\Code\ConsecutiveNumbersCombinationsInGeneratedSetsCondition;
use App\Services\Api\V1\LotteryCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConsecutiveNumbersCombinationsInGeneratedSetsConditionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Condition $condition;

    public function setUp(): void
    {
        parent::setUp();

        $this->condition = new ConsecutiveNumbersCombinationsInGeneratedSetsCondition;
    }

    public function test_handle()
    {
        $lotteryCodeService = new LotteryCodeService;

        $lottery = Lottery::where('numbers_count', 5)->first();
        $code = [];

        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $code[] = $lottery->numbers_from + $i;
        }

        $lotteryCodeService->storeLotteryCode($lottery, $code, true);

        $code = [2, 3, 4, 5, 7];

        $result = $this->condition->handle($code);

        $this->assertFalse($result);
    }

    public function test_handle_not_consecutive_numbers_combinations_code()
    {
        $lotteryCodeService = new LotteryCodeService;

        $lottery = Lottery::where('numbers_count', 5)->first();
        $code = [];

        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $code[] = $lottery->numbers_from + $i;
        }

        $lotteryCodeService->storeLotteryCode($lottery, $code, true);

        $code = [2, 4, 6, 7, 8];

        $result = $this->condition->handle($code);

        $this->assertTrue($result);
    }
}
