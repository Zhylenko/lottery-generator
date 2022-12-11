<?php

namespace Tests\Feature\Observers\Api\V1;

use App\Models\Api\V1\Lottery;
use App\Services\Api\V1\LotteryCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LotteryCodeObserverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_generated_deleted_after_lottery_code_generated_deleted()
    {
        $lotteryCodeService = new LotteryCodeService;

        $lottery = Lottery::inRandomOrder()->first();
        $code = [];

        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $code[] = $lottery->numbers_from + $i;
        }

        $lotteryCodeGenerated = $lotteryCodeService->storeLotteryCode($lottery, $code, true);
        $generated = $lotteryCodeGenerated->generated;

        $this->assertModelExists($lotteryCodeGenerated);
        $this->assertModelExists($generated);

        $lotteryCodeGenerated->delete();

        $this->assertModelMissing($lotteryCodeGenerated);
        $this->assertModelMissing($generated);
    }
}
