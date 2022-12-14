<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LotteryCodeSpecialControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_index()
    {
        $response = $this->getJson(Route('lottery.code.special.index'));

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

    public function test_get_index_cached()
    {
        Cache::tags(['lotteries', 'codes', 'special'])->flush();
        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_1'));

        $this->test_get_index();

        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_1'));
    }

    public function test_get_index_by_lottery()
    {
        $lottery = Lottery::has('codes.special')
            ->inRandomOrder()
            ->first();

        $response = $this->getJson(Route('lottery.code.special.lottery.index', $lottery));

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
            ])->assertJsonFragment([
                'lottery' => [
                    'id' => $lottery->id,
                    'name' => $lottery->name,
                    'numbers_count' => $lottery->numbers_count,
                    'numbers_from' => $lottery->numbers_from,
                    'numbers_to' => $lottery->numbers_to,
                ],
            ]);
    }

    public function test_get_index_by_lottery_cached()
    {
        $lottery = Lottery::has('codes.special')
            ->inRandomOrder()
            ->first();

        Cache::tags(['lotteries', 'codes', 'special', 'lottery_' . $lottery->id])->flush();
        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special', 'lottery_' . $lottery->id])->has('page_1'));

        $this->getJson(Route('lottery.code.special.lottery.index', $lottery));

        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special', 'lottery_' . $lottery->id])->has('page_1'));
    }
}
