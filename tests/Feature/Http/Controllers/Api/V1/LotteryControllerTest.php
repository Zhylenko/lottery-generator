<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LotteryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_index()
    {
        $response = $this->getJson(Route('lottery.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'numbers_count',
                        'numbers_from',
                        'numbers_to',
                    ],
                ],
            ]);
    }

    public function test_get_index_cached()
    {
        Cache::tags(['lotteries'])->flush();
        $this->assertFalse(Cache::tags(['lotteries'])->has('page_1'));

        $this->test_get_index();

        $this->assertTrue(Cache::tags(['lotteries'])->has('page_1'));
    }

    public function test_get_show()
    {
        $lottery = Lottery::all()->random();

        $response = $this->getJson(Route('lottery.show', $lottery));

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $lottery->id,
                    'name' => $lottery->name,
                    'numbers_count' => $lottery->numbers_count,
                    'numbers_from' => $lottery->numbers_from,
                    'numbers_to' => $lottery->numbers_to,
                ],
            ]);
    }

    public function test_store_lottery()
    {
        $data = Lottery::factory()->make()->getAttributes();

        $response = $this->postJson(Route('lottery.store'), $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $data['name'],
                    'numbers_count' => $data['numbers_count'],
                    'numbers_from' => $data['numbers_from'],
                    'numbers_to' => $data['numbers_to'],
                ],
            ]);
    }

    public function test_update_lottery()
    {
        $lottery = Lottery::all()->random();
        $data = Lottery::factory()->make()->getAttributes();

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $lottery->id,
                    'name' => $data['name'],
                    'numbers_count' => $data['numbers_count'],
                    'numbers_from' => $data['numbers_from'],
                    'numbers_to' => $data['numbers_to'],
                ],
            ]);
    }

    public function test_destroy_lottery()
    {
        $lottery = Lottery::all()->random();

        $response = $this->deleteJson(Route('lottery.destroy', $lottery));

        $response->assertStatus(200)
            ->assertExactJson([1]);

        $this->assertModelMissing($lottery);
    }
}
