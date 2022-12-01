<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Api\V1\Code;
use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
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

    public function test_get_index_cached()
    {
        Cache::tags(['lotteries', 'codes'])->flush();
        $this->assertFalse(Cache::tags(['lotteries', 'codes'])->has('page_1'));

        $this->test_get_index();

        $this->assertTrue(Cache::tags(['lotteries', 'codes'])->has('page_1'));
    }

    public function test_get_index_by_lottery()
    {
        $lottery = Lottery::has('codes')->inRandomOrder()->first();

        $response = $this->getJson(Route('lottery.code.lottery.index', $lottery));

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
        $lottery = Lottery::has('codes')->inRandomOrder()->first();

        Cache::tags(['lotteries', 'codes', 'lottery_' . $lottery->id])->flush();
        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'lottery_' . $lottery->id])->has('page_1'));

        $this->getJson(Route('lottery.code.lottery.index', $lottery));

        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'lottery_' . $lottery->id])->has('page_1'));
    }

    public function test_get_show()
    {
        $lotteryCode = Code::has('lotteries')->inRandomOrder()->first();
        $lottery = $lotteryCode->lotteries()->first();

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
                ],
            ]);
    }

    public function test_get_show_not_lottery_code_not_found_error()
    {
        $lotteryCode = Code::doesntHave('lotteries')->inRandomOrder()->first();

        $response = $this->getJson(Route('lottery.code.show', $lotteryCode));

        $response->assertStatus(404);
    }

    public function test_store_lottery_code()
    {
        $lottery = Lottery::inRandomOrder()->first();
        // $data = Code::factory()->make()->toArray();
        $data = ['code' => []];
        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $data['code'][] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'code' => $data['code'],
                    'lottery' => [
                        'id' => $lottery->id,
                        'name' => $lottery->name,
                        'numbers_count' => $lottery->numbers_count,
                        'numbers_from' => $lottery->numbers_from,
                        'numbers_to' => $lottery->numbers_to,
                    ],
                ],
            ]);
    }
}
