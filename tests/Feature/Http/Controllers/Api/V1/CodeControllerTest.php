<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
