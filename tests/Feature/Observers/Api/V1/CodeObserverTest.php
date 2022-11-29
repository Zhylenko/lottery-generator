<?php

namespace Tests\Feature\Observers\Api\V1;

use App\Models\Api\V1\Code;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CodeObserverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_code_field_type_converts_to_array_of_integers()
    {
        $data = [
            'code' => [1, "3"],
        ];

        $response = $this->postJson(Route('code.store'), $data);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'code' => array_map('intval', $data['code']),
            ])
            ->assertJsonMissing([
                'code' => $data['code'],
            ]);
    }

    public function test_cache_deleted_after_code_created()
    {
        $page = 1;
        $uri = Route('code.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['codes'])->has('page_' . $page));

        $data = Code::factory()->make()->toArray();

        $response = $this->postJson(Route('code.store'), $data);
        $response->assertStatus(201);

        $this->assertFalse(Cache::tags(['codes'])->has('page_' . $page));
    }
}
