<?php

namespace Tests\Feature\Observers\Api\V1;

use App\Models\Api\V1\Code;
use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LotteryCodeSpecialCodeObserverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_cache_deleted_after_code_created()
    {
        $page = 1;
        $uri = Route('lottery.code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));

        $data = Code::factory()->make()->toArray();

        $response = $this->postJson(Route('code.store'), $data);
        $response->assertStatus(201);

        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_code_updated()
    {
        $page = 1;
        $uri = Route('lottery.code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));

        $code = Code::inRandomOrder()->first();
        $data = Code::factory()->make()->toArray();

        $response = $this->putJson(Route('code.update', $code), $data);
        $response->assertStatus(200);

        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_code_deleted()
    {
        $page = 1;
        $uri = Route('lottery.code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));

        $code = Code::inRandomOrder()->first();

        $response = $this->deleteJson(Route('code.destroy', $code));

        $response->assertStatus(200)
            ->assertExactJson([1]);
        $this->assertModelMissing($code);

        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_lottery_code_created()
    {
        $page = 1;
        $uri = Route('lottery.code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));

        $lottery = Lottery::inRandomOrder()->first();
        // $data = Code::factory()->make()->toArray();
        $data = ['code' => []];
        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $data['code'][] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(201);

        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_lottery_code_updated()
    {
        $page = 1;
        $uri = Route('lottery.code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));

        $lotteryCode = Code::has('lotteries')->inRandomOrder()->first();
        $lottery = $lotteryCode->lotteries()->first();
        // $data = Code::factory()->make()->toArray();
        $data = ['code' => []];
        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $data['code'][] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        $data['lottery'] = $lottery->id;

        $response = $this->putJson(Route('lottery.code.update', $lotteryCode), $data);

        $response->assertStatus(200);

        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_lottery_code_deleted()
    {
        $page = 1;
        $uri = Route('lottery.code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));

        $lotteryCode = Code::has('lotteries')->inRandomOrder()->first();

        $response = $this->deleteJson(Route('lottery.code.destroy', $lotteryCode));

        $response->assertStatus(200)
            ->assertExactJson([1]);

        $this->assertModelMissing($lotteryCode);

        $this->assertFalse(Cache::tags(['lotteries', 'codes', 'special'])->has('page_' . $page));
    }
}
