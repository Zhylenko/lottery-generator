<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LotteryCodeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_index()
    {
        $response = $this->getJson(Route('lottery.code.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'code',
                        'lottery' => [
                            'id',
                            'name',
                            'numbers_count',
                            'numbers_from',
                            'numbers_to',
                        ],
                    ],
                ],
            ]);
    }

    public function test_get_index_with_lottery()
    {
        $lottery = Lottery::all()->random();

        $response = $this->getJson(Route('lottery.code.index.lottery', $lottery));

        $response->assertStatus(200);

        if (!empty($lottery->codes()->count()))
            $response->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'code',
                        'lottery' => [
                            'id',
                            'name',
                            'numbers_count',
                            'numbers_from',
                            'numbers_to',
                        ],
                    ],
                ],
            ]);
        else
            $response->assertJsonCount(0);
    }

    public function test_get_show()
    {
        $lotteryCode = LotteryCode::all()->random();
        $lottery = $lotteryCode->lottery;

        $response = $this->getJson(Route('lottery.code.show', $lotteryCode));

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $lotteryCode->id,
                    'code' => $lotteryCode->code,
                    'lottery' => [
                        'id' => $lottery->id,
                        'name' => $lottery->name,
                        'numbers_count' => $lottery->numbers_count,
                        'numbers_from' => $lottery->numbers_from,
                        'numbers_to' => $lottery->numbers_to,
                    ],
                ]
            ]);
    }
}
