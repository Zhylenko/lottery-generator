<?php

namespace Tests\Feature\Services\Api\V1;

use App\Contracts\Api\V1\LotteryCode;
use App\Models\Api\V1\Lottery;
use App\Services\Api\V1\LotteryCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class LotteryCodeServiceTest extends TestCase
{
    use RefreshDatabase;

    private LotteryCode $lotteryCodeService;

    public function setUp(): void
    {
        parent::setUp();

        $this->lotteryCodeService = new LotteryCodeService;
    }

    public function test_generate_lottery_code()
    {
        $lottery = Lottery::inRandomOrder()->first();

        $code = $this->lotteryCodeService->generateLotteryCode($lottery);

        $this->assertIsArray($code);
        $this->assertCount($lottery->numbers_count, $code);
    }

    public function test_generated_lottery_code_elements_in_lottery_numbers_range()
    {
        $lottery = Lottery::inRandomOrder()->first();
        $lotteryCodeNumbers = Collection::range($lottery->numbers_from, $lottery->numbers_to)->values()->all();

        $code = $this->lotteryCodeService->generateLotteryCode($lottery);

        $this->assertIsArray($code);
        $this->assertCount($lottery->numbers_count, $code);

        foreach ($code as $value) {
            $this->assertContains($value, $lotteryCodeNumbers);
        }
    }

    public function test_generate_lottery_code_distinct()
    {
        $lottery = Lottery::inRandomOrder()->first();

        $code = $this->lotteryCodeService->generateLotteryCode($lottery, true);
        $codeCollection = new Collection($code);

        $this->assertIsArray($code);
        $this->assertCount($lottery->numbers_count, $code);
        $this->assertTrue($codeCollection->duplicates()->isEmpty());
    }

    public function test_generate_lottery_code_not_distinct()
    {
        $lottery = Lottery::make([
            'name' => 'Test lottery',
            'numbers_count' => 2,
            'numbers_from' => 1,
            'numbers_to' => 1,
        ]);

        $code = $this->lotteryCodeService->generateLotteryCode($lottery, false);
        $codeCollection = new Collection($code);

        $this->assertIsArray($code);
        $this->assertCount($lottery->numbers_count, $code);
        $this->assertTrue($codeCollection->duplicates()->isNotEmpty());
    }

    public function test_store_lottery_code_generated()
    {
        $lottery = Lottery::inRandomOrder()->first();
        $code = $this->lotteryCodeService->generateLotteryCode($lottery, true);

        $lotteryCode = $this->lotteryCodeService->storeLotteryCode($lottery, $code, true);

        $this->assertModelExists($lotteryCode);
        $this->assertNotEmpty($lotteryCode->generated);
        $this->assertModelExists($lotteryCode->generated);
    }
}
