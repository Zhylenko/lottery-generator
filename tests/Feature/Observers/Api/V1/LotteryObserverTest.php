<?php

namespace Tests\Feature\Observers\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LotteryObserverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // public function test_lottery_codes_delete_on_lottery_numbers_count_field_updating()
    // {
    //     $lottery = Lottery::all()->random();

    //     $data = $lottery->toArray();
    //     $data['numbers_count'] += 1;

    //     $response = $this->putJson(Route('lottery.update', $lottery), $data);

    //     $response->assertStatus(200)
    //         ->assertExactJson([
    //             'data' => [
    //                 'id' => $lottery->id,
    //                 'name' => $data['name'],
    //                 'numbers_count' => $data['numbers_count'],
    //                 'numbers_from' => $data['numbers_from'],
    //                 'numbers_to' => $data['numbers_to'],
    //             ],
    //         ]);

    //     $this->assertFalse($lottery->codes()->exists());
    // }

    // public function test_lottery_codes_delete_on_lottery_numbers_from_field_updating()
    // {
    //     $lottery = Lottery::all()->random();

    //     $data = $lottery->toArray();
    //     $data['numbers_from'] -= 1;

    //     $response = $this->putJson(Route('lottery.update', $lottery), $data);

    //     $response->assertStatus(200)
    //         ->assertExactJson([
    //             'data' => [
    //                 'id' => $lottery->id,
    //                 'name' => $data['name'],
    //                 'numbers_count' => $data['numbers_count'],
    //                 'numbers_from' => $data['numbers_from'],
    //                 'numbers_to' => $data['numbers_to'],
    //             ],
    //         ]);

    //     $this->assertFalse($lottery->codes()->exists());
    // }

    // public function test_lottery_codes_delete_on_lottery_numbers_to_field_updating()
    // {
    //     $lottery = Lottery::all()->random();

    //     $data = $lottery->toArray();
    //     $data['numbers_to'] += 1;

    //     $response = $this->putJson(Route('lottery.update', $lottery), $data);

    //     $response->assertStatus(200)
    //         ->assertExactJson([
    //             'data' => [
    //                 'id' => $lottery->id,
    //                 'name' => $data['name'],
    //                 'numbers_count' => $data['numbers_count'],
    //                 'numbers_from' => $data['numbers_from'],
    //                 'numbers_to' => $data['numbers_to'],
    //             ],
    //         ]);

    //     $this->assertFalse($lottery->codes()->exists());
    // }

    // public function test_lottery_codes_delete_after_lottery_deleted()
    // {
    //     $lottery = Lottery::all()->random();

    //     $response = $this->deleteJson(Route('lottery.destroy', $lottery));

    //     $response->assertStatus(200)
    //         ->assertExactJson([1]);

    //     $this->assertModelMissing($lottery);
    //     $this->assertFalse($lottery->codes()->exists());
    // }

    public function test_cache_deleted_after_lottery_created()
    {
        $page = 1;
        $uri = Route('lottery.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries'])->has('page_' . $page));

        $data = Lottery::factory()->make()->toArray();

        $response = $this->postJson(Route('lottery.store'), $data);
        $response->assertStatus(201);

        $this->assertFalse(Cache::tags(['lotteries'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_lottery_updated()
    {
        $page = 1;
        $uri = Route('lottery.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries'])->has('page_' . $page));

        $lottery = Lottery::all()->random();
        $data = Lottery::factory()->make()->toArray();

        $response = $this->putJson(Route('lottery.update', $lottery), $data);
        $response->assertStatus(200);

        $this->assertFalse(Cache::tags(['lotteries'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_lottery_deleted()
    {
        $page = 1;
        $uri = Route('lottery.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['lotteries'])->has('page_' . $page));

        $lottery = Lottery::all()->random();

        $response = $this->deleteJson(Route('lottery.destroy', $lottery));
        $response->assertStatus(200)
            ->assertExactJson([1]);
        $this->assertModelMissing($lottery);

        $this->assertFalse(Cache::tags(['lotteries'])->has('page_' . $page));
    }
}
