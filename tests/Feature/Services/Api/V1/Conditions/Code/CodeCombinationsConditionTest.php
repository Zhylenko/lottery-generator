<?php

namespace Tests\Feature\Services\Api\V1\Conditions\Code;

use App\Contracts\Api\V1\Condition;
use App\Models\Api\V1\Lottery;
use App\Services\Api\V1\Conditions\Code\CodeCombinationsCondition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CodeCombinationsConditionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Condition $condition;

    public function setUp(): void
    {
        parent::setUp();

        $this->conditionClass = CodeCombinationsCondition::class;
    }

    public function test_handle()
    {
        $lottery = Lottery::has('codes')->inRandomOrder()->first();

        $oldestLotteryCode = $lottery->codes()->oldest('id')->first();
        $latestLotteryCode = $lottery->codes()->latest('id')->first();

        $randomLotteryCode = $lottery->codes()->inRandomOrder()->first();

        $data = [
            'numbers' => $lottery->numbers_count,
            'count' => 100,
            'from' => $oldestLotteryCode->id,
            'to' => $latestLotteryCode->id,
        ];

        $conditionClass = $this->conditionClass;
        $condition = new $conditionClass($lottery, $data['numbers'], $data['count'], $data['from'], $data['to']);

        $result = $condition->handle($randomLotteryCode->code);

        $this->assertFalse($result);
    }

    public function test_handle_not_in_range_code_not_error()
    {
        $lottery = Lottery::has('codes')->inRandomOrder()->first();

        $oldestLotteryCode = $lottery->codes()->oldest('id')->first();
        $latestLotteryCode = $lottery->codes()->latest('id')->first();

        $data = [
            'numbers' => $lottery->numbers_count,
            'count' => 100,
            'from' => $oldestLotteryCode->id,
            'to' => $latestLotteryCode->id - 1,
        ];

        $conditionClass = $this->conditionClass;
        $condition = new $conditionClass($lottery, $data['numbers'], $data['count'], $data['from'], $data['to']);

        $result = $condition->handle($latestLotteryCode->code);

        $this->assertTrue($result);
    }
}
