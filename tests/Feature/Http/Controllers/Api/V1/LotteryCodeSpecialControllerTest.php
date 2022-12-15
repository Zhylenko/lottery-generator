<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode;
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

    public function test_get_show()
    {
        $lotteryCodeSpecial = LotteryCode::has('special')
            ->inRandomOrder()
            ->first();

        $lottery = $lotteryCodeSpecial->lottery;
        $code = $lotteryCodeSpecial->code;

        $response = $this->getJson(Route('lottery.code.special.show', $code));

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $code->id,
                    'code' => $code->code,
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

    public function test_get_show_lottery_code_not_special_not_found_error()
    {
        $lotteryCode = LotteryCode::doesntHave('special')
            ->inRandomOrder()
            ->first();

        $code = $lotteryCode->code;

        $response = $this->getJson(Route('lottery.code.special.show', $code));

        $response->assertStatus(404);
    }

    public function test_store_lottery_code_special()
    {
        $lottery = Lottery::inRandomOrder()->first();
        $data = [];

        $data['code'] = \LotteryCodeService::generateLotteryCode($lottery, true);

        $response = $this->postJson(Route('lottery.code.special.store', $lottery), $data);

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

    public function test_update_lottery_code_special_code()
    {
        $lotteryCodeSpecial = LotteryCode::has('special')
            ->inRandomOrder()
            ->first();
        $data = [];

        $lottery = $lotteryCodeSpecial->lottery;
        $code = $lotteryCodeSpecial->code;

        $data['code'] = \LotteryCodeService::generateLotteryCode($lottery, true);
        $data['lottery'] = $lottery->id;

        $response = $this->putJson(Route('lottery.code.special.update', $code), $data);

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $code->id,
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

    public function test_update_not_lottery_code_special_code_not_found_error()
    {
        $lotteryCode = LotteryCode::doesntHave('special')
            ->inRandomOrder()
            ->first();

        $lottery = $lotteryCode->lottery;
        $code = $lotteryCode->code;

        $data['code'] = \LotteryCodeService::generateLotteryCode($lottery, true);
        $data['lottery'] = $lottery->id;

        $response = $this->putJson(Route('lottery.code.special.update', $code), $data);

        $response->assertStatus(404);
    }

    public function test_update_lottery_code_special_lottery()
    {
        $lotteryCodeSpecial = LotteryCode::has('special')
            ->inRandomOrder()
            ->first();
        $data = [];

        $lottery = $lotteryCodeSpecial->lottery;
        $code = $lotteryCodeSpecial->code;

        $newLottery = Lottery::whereRelation('codes', 'code_id', '!=', $code->id)
            ->where('id', '!=', $lottery->id)
            ->inRandomOrder()
            ->first();

        $data['code'] = \LotteryCodeService::generateLotteryCode($newLottery, true);
        $data['lottery'] = $newLottery->id;

        $response = $this->putJson(Route('lottery.code.special.update', $code), $data);

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $code->id,
                    'code' => $data['code'],
                    'lottery' => [
                        'id' => $newLottery->id,
                        'name' => $newLottery->name,
                        'numbers_count' => $newLottery->numbers_count,
                        'numbers_from' => $newLottery->numbers_from,
                        'numbers_to' => $newLottery->numbers_to,
                    ],
                ],
            ]);

        $this->assertFalse($lottery->codes()->where('codes.id', $lottery->id)->exists());
    }
}
