<?php

namespace Tests\Feature\Services\Api\V1\Conditions\Code;

use App\Contracts\Api\V1\Condition;
use App\Models\Api\V1\Lottery;
use App\Services\Api\V1\Conditions\Code\CodeCombinationsCondition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CodeCombinationsConditionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $conditionClass;

    public function setUp(): void
    {
        parent::setUp();

        $this->conditionClass = CodeCombinationsCondition::class;
    }

    public function test_handle()
    {
        $lottery = Lottery::whereHas('codes', function (Builder $query) {
            $query
                ->doesntHave('special')
                ->doesntHave('generated');
        })
            ->inRandomOrder()
            ->first();

        $oldestLotteryCode = $lottery
            ->codes()
            ->doesntHave('special')
            ->doesntHave('generated')
            ->oldest('id')
            ->first();

        $latestLotteryCode = $lottery
            ->codes()
            ->doesntHave('special')
            ->doesntHave('generated')
            ->latest('id')
            ->first();

        $randomLotteryCode = $lottery
            ->codes()
            ->doesntHave('special')
            ->doesntHave('generated')
            ->inRandomOrder()
            ->first();

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
        $lottery = Lottery::whereHas('codes', function (Builder $query) {
            $query
                ->doesntHave('special')
                ->doesntHave('generated');
        })
            ->inRandomOrder()
            ->first();

        $oldestLotteryCode = $lottery
            ->codes()
            ->doesntHave('special')
            ->doesntHave('generated')
            ->oldest('id')
            ->first();

        $latestLotteryCode = $lottery
            ->codes()
            ->doesntHave('special')
            ->doesntHave('generated')
            ->latest('id')
            ->first();

        $data = [
            'numbers' => $lottery->numbers_count,
            'count' => 2,
            'from' => $oldestLotteryCode->id,
            'to' => $latestLotteryCode->id - 1,
        ];

        $conditionClass = $this->conditionClass;
        $condition = new $conditionClass($lottery, $data['numbers'], $data['count'], $data['from'], $data['to']);

        $result = $condition->handle($latestLotteryCode->code);

        $this->assertTrue($result);
    }

    public function test_handle_lottery_code_special_not_error()
    {
        $lottery = Lottery::whereHas('codes', function (Builder $query) {
            $query->has('special');
        })
            ->inRandomOrder()
            ->first();

        $oldestLotteryCode = $lottery
            ->codes()
            ->oldest('id')
            ->first();

        $latestLotteryCode = $lottery
            ->codes()
            ->latest('id')
            ->first();

        $specialLotteryCode = $lottery
            ->codes()
            ->has('special')
            ->inRandomOrder()
            ->first();

        $data = [
            'numbers' => $lottery->numbers_count,
            'count' => 100,
            'from' => $oldestLotteryCode->id,
            'to' => $latestLotteryCode->id,
        ];

        $conditionClass = $this->conditionClass;
        $condition = new $conditionClass($lottery, $data['numbers'], $data['count'], $data['from'], $data['to']);

        $result = $condition->handle($specialLotteryCode->code);

        $this->assertTrue($result);
    }

    public function test_handle_lottery_code_generated_not_error()
    {
        $randomLottery = Lottery::inRandomOrder()
            ->first();

        $code = \LotteryCodeService::generateLotteryCode($randomLottery, true);

        \LotteryCodeService::storeLotteryCode($randomLottery, $code, true);

        $lottery = Lottery::whereHas('codes', function (Builder $query) {
            $query->has('generated');
        })
            ->inRandomOrder()
            ->first();

        $oldestLotteryCode = $lottery
            ->codes()
            ->oldest('id')
            ->first();

        $latestLotteryCode = $lottery
            ->codes()
            ->latest('id')
            ->first();

        $lotteryCodeGenerated = $lottery
            ->codes()
            ->has('generated')
            ->inRandomOrder()
            ->first();

        $data = [
            'numbers' => $lottery->numbers_count,
            'count' => 100,
            'from' => $oldestLotteryCode->id,
            'to' => $latestLotteryCode->id,
        ];

        $conditionClass = $this->conditionClass;
        $condition = new $conditionClass($lottery, $data['numbers'], $data['count'], $data['from'], $data['to']);

        $result = $condition->handle($lotteryCodeGenerated->code);

        $this->assertTrue($result);
    }
}
