<?php

namespace Tests\Feature\Observers\Api\V1;

use App\Models\Api\V1\Code;
use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LotteryCodeSpecialLotteryObserverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_cache_deleted_after_lottery_created()
    {
        $page = 1;
        $uri = Route('lottery.code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));

        $data = Lottery::factory()->make()->toArray();

        $response = $this->postJson(Route('lottery.store'), $data);
        $response->assertStatus(201);

        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_lottery_updated()
    {
        $page = 1;
        $uri = Route('lottery.code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));

        $lottery = Lottery::has('codes')->inRandomOrder()->first();
        $data = Lottery::factory()->make()->toArray();

        $response = $this->putJson(Route('lottery.update', $lottery), $data);
        $response->assertStatus(200);

        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_lottery_deleted()
    {
        $page = 1;
        $uri = Route('lottery.code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));

        $lottery = Lottery::has('codes')->inRandomOrder()->first();

        $response = $this->deleteJson(Route('lottery.destroy', $lottery));
        $response->assertStatus(200)
            ->assertExactJson([1]);
        $this->assertModelMissing($lottery);

        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_lottery_code_lottery_updated()
    {
        $page = 1;
        $uri = Route('lottery.code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));

        $lotteryCode = Code::has('lotteries')->inRandomOrder()->first();
        $codeLottery = $lotteryCode->lotteries()->first();

        $lottery = Lottery::has('codes')->whereRelation('codes', 'code_id', '!=', $lotteryCode->id)->where('id', '!=', $codeLottery->id)->inRandomOrder()->first();
        
        $data = ['code' => []];

        $data['code'] = \LotteryCodeService::generateLotteryCode($lottery, true);

        $data['lottery'] = $lottery->id;

        $response = $this->putJson(Route('lottery.code.update', $lotteryCode), $data);

        $response->assertStatus(200);

        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));
    }
}
