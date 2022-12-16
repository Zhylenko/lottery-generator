<?php

namespace Tests\Feature\Services\Api\V1\Conditions\Code;

use App\Contracts\Api\V1\Condition;
use App\Models\Api\V1\LotteryCode;
use App\Models\Api\V1\LotteryCodeSpecial;
use App\Services\Api\V1\Conditions\Code\SpecialCodeCondition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpecialCodeConditionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $conditionClass;

    public function setUp(): void
    {
        parent::setUp();

        $this->conditionClass = SpecialCodeCondition::class;
    }

    public function test_handle()
    {
        $lotteryCodeSpecial = LotteryCodeSpecial::inRandomOrder()
            ->first();

        $lottery = $lotteryCodeSpecial->lotteryCode->lottery;
        $code = $lotteryCodeSpecial->lotteryCode->code;

        $conditionClass = $this->conditionClass;
        $condition = new $conditionClass($lottery);

        $result = $condition->handle($code->code);

        $this->assertFalse($result);
    }

    public function test_handle_not_special_code()
    {
        $lotteryCode = LotteryCode::whereHas('code', function (Builder $query) {
            $query->doesntHave('special');
        })
            ->inRandomOrder()
            ->first();

        $lottery = $lotteryCode->lottery;
        $code = $lotteryCode->code;

        $conditionClass = $this->conditionClass;
        $condition = new $conditionClass($lottery);

        $result = $condition->handle($code->code);

        $this->assertTrue($result);
    }
}
