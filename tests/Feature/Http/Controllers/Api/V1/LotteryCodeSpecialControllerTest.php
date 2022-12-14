<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

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
}
