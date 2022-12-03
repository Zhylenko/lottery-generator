<?php

namespace Tests\Feature\Observers\Api\V1;

use App\Models\Api\V1\Code;
use App\Models\Api\V1\SpecialCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SpecialCodeCodeObserverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_special_code_deleted_after_special_code_code_deleted()
    {
        $specialCode = SpecialCode::has('code')->inRandomOrder()->first();
        $code = $specialCode->code;

        $response = $this->deleteJson(Route('code.destroy', $code));

        $response->assertStatus(200)
            ->assertExactJson([1]);
        $this->assertModelMissing($code);

        $this->assertModelMissing($specialCode);
    }

    public function test_cache_deleted_after_special_code_code_updated()
    {
        $page = 1;
        $uri = Route('code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['codes', 'special'])->has('page_' . $page));

        $specialCode = Code::has('special')->inRandomOrder()->first();
        $data = Code::factory()->make()->toArray();

        $response = $this->putJson(Route('code.update', $specialCode), $data);

        $response->assertStatus(200);

        $this->assertFalse(Cache::tags(['codes', 'special'])->has('page_' . $page));
    }

    public function test_cache_deleted_after_special_code_code_deleted()
    {
        $page = 1;
        $uri = Route('code.special.index', ['page' => $page]);

        $this->getJson($uri);
        $this->assertTrue(Cache::tags(['codes', 'special'])->has('page_' . $page));

        $specialCode = SpecialCode::has('code')->inRandomOrder()->first();
        $code = $specialCode->code;

        $response = $this->deleteJson(Route('code.destroy', $code));

        $response->assertStatus(200)
            ->assertExactJson([1]);

        $this->assertModelMissing($code);
        $this->assertModelMissing($specialCode);

        $this->assertFalse(Cache::tags(['codes', 'special'])->has('page_' . $page));
    }
}
