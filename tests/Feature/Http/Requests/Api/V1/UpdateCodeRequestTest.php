<?php

namespace Tests\Feature\Http\Requests\Api\V1;

use App\Models\Api\V1\Code;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCodeRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_empty_fields_error()
    {
        $code = Code::inRandomOrder()->first();

        $data = [
            'code' => '',
        ];

        $response = $this->putJson(Route('code.update', $code), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code',
            ]);
    }

    public function test_not_array_fields_error()
    {
        $code = Code::inRandomOrder()->first();

        $data = [
            'code' => $this->faker->word(),
        ];

        $response = $this->putJson(Route('code.update', $code), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code',
            ]);
    }

    public function test_array_elements_not_integer_fields_error()
    {
        $code = Code::inRandomOrder()->first();

        $data = [
            'code' => [1, $this->faker->word()],
        ];

        $response = $this->putJson(Route('code.update', $code), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code.1',
            ]);
    }

    public function test_array_elements_float_fields_error()
    {
        $code = Code::inRandomOrder()->first();

        $data = [
            'code' => [1, $this->faker->randomFloat(5, -10, 10)],
        ];

        $response = $this->putJson(Route('code.update', $code), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code.1',
            ]);
    }

    // public function test_array_elements_not_distinct_fields_error()
    // {
    //     $code = Code::inRandomOrder()->first();

    //     $data = [
    //         'code' => [1, 1],
    //     ];

    //     $response = $this->putJson(Route('code.update', $code), $data);

    //     $response->assertStatus(422)
    //         ->assertJsonValidationErrors([
    //             'code.1',
    //         ]);
    // }
}
