<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CodeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_index()
    {
        $response = $this->getJson(Route('code.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'code',
                    ],
                ],
            ]);
    }

    public function test_get_index_cached()
    {
        Cache::tags(['codes'])->flush();
        $this->assertFalse(Cache::tags(['codes'])->has('page_1'));

        $this->test_get_index();

        $this->assertTrue(Cache::tags(['codes'])->has('page_1'));
    }
}
