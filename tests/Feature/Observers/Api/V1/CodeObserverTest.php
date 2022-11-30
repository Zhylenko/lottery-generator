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

    public function test_code_field_formatted_after_code_created()
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

    public function test_code_field_formatted_after_code_updated()
    {
        $code = Code::all()->random();

        $data = [
            'code' => [1, "3"],
        ];

        $response = $this->putJson(Route('code.update', $code), $data);

        $response->assertStatus(200)
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

    public function test_cache_deleted_after_code_updated()
    {
        $page = 1;
        $uri = Route('code.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['codes'])->has('page_' . $page));

        $code = Code::all()->random();
        $data = Code::factory()->make()->toArray();

        $response = $this->putJson(Route('code.update', $code), $data);
        $response->assertStatus(200);

        $this->assertFalse(Cache::tags(['codes'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_code_deleted()
    {
        $page = 1;
        $uri = Route('code.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['codes'])->has('page_' . $page));

        $code = Code::all()->random();

        $response = $this->deleteJson(Route('code.destroy', $code));

        $response->assertStatus(200)
            ->assertExactJson([1]);
        $this->assertModelMissing($code);

        $this->assertFalse(Cache::tags(['codes'])->has('page_' . $page));
    }
}
