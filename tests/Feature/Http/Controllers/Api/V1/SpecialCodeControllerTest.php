<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Api\V1\Code;
use App\Models\Api\V1\SpecialCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SpecialCodeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_index()
    {
        $response = $this->getJson(Route('code.special.index'));

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
        Cache::tags(['codes', 'special'])->flush();
        $this->assertFalse(Cache::tags(['codes', 'special'])->has('page_1'));

        $this->test_get_index();

        $this->assertTrue(Cache::tags(['codes', 'special'])->has('page_1'));
    }

    public function test_get_show()
    {
        $code = Code::has('special')->inRandomOrder()->first();

        $response = $this->getJson(Route('code.special.show', $code));

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $code->id,
                    'code' => $code->code,
                ],
            ]);
    }

    public function test_get_show_not_special_code_not_found_error()
    {
        $code = Code::doesntHave('special')->inRandomOrder()->first();

        $response = $this->getJson(Route('code.special.show', $code));

        $response->assertStatus(404);
    }

    public function test_store_special_code()
    {
        $data = Code::factory()->make()->toArray();

        $response = $this->postJson(Route('code.special.store'), $data);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'code' => $data['code'],
                ],
            ]);
    }

    public function test_update_special_code()
    {
        $specialCode = Code::has('special')->inRandomOrder()->first();
        $data = Code::factory()->make()->toArray();

        $response = $this->putJson(Route('code.special.update', $specialCode), $data);

        $response->assertStatus(200)
            ->assertExactJson([
                'data' => [
                    'id' => $specialCode->id,
                    'code' => $data['code'],
                ],
            ]);
    }

    public function test_update_not_special_code_not_found_error()
    {
        $code = Code::doesntHave('special')->inRandomOrder()->first();
        $data = Code::factory()->make()->toArray();

        $response = $this->putJson(Route('code.special.update', $code), $data);

        $response->assertStatus(404);
    }

    public function test_destroy_special_code()
    {
        $specialCode = SpecialCode::has('code')->inRandomOrder()->first();
        $code = $specialCode->code;

        $response = $this->deleteJson(Route('code.special.destroy', $code));

        $response->assertStatus(200)
            ->assertExactJson([1]);

        $this->assertModelMissing($code);
        $this->assertModelMissing($specialCode);
    }

    public function test_destroy_not_special_code_not_found_error()
    {
        $code = Code::doesntHave('special')->inRandomOrder()->first();

        $response = $this->deleteJson(Route('code.special.destroy', $code));

        $response->assertStatus(404);
    }
}
