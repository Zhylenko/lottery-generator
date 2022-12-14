<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Api\V1\Code;
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

    public function test_get_show()
    {
        $code = Code::inRandomOrder()->first();

        $response = $this->getJson(Route('code.show', $code));

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $code->id,
                    'code' => $code->code,
                ],
            ]);
    }

    public function test_store_code()
    {
        $data = Code::factory()->make()->toArray();

        $response = $this->postJson(Route('code.store'), $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'code' => $data['code'],
                ],
            ]);
    }

    public function test_update_code()
    {
        $code = Code::inRandomOrder()->first();
        $data = Code::factory()->make()->toArray();

        $response = $this->putJson(Route('code.update', $code), $data);

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $code->id,
                    'code' => $data['code'],
                ],
            ]);
    }

    public function test_destroy_code()
    {
        $code = Code::inRandomOrder()->first();

        $response = $this->deleteJson(Route('code.destroy', $code));

        $response->assertStatus(200)
            ->assertExactJson([1]);

        $this->assertModelMissing($code);
    }
}
