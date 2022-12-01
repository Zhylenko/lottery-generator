<?php

namespace Tests\Feature\Http\Requests\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreLotteryCodeRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_empty_fields_error()
    {
        $lottery = Lottery::inRandomOrder()->first();

        $data = [
            'code' => '',
        ];

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code',
            ]);
    }

    public function test_not_array_fields_error()
    {
        $lottery = Lottery::inRandomOrder()->first();

        $data = [
            'code' => $this->faker->word(),
        ];

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code',
            ]);
    }

    public function test_array_elements_not_integer_fields_error()
    {
        $lottery = Lottery::inRandomOrder()->first();
        // $data = Code::factory()->make()->toArray();
        $data = ['code' => []];
        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $data['code'][] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        $data['code'][0] = $this->faker->word();

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code.0',
            ]);
    }

    public function test_array_elements_float_fields_error()
    {
        $lottery = Lottery::inRandomOrder()->first();
        // $data = Code::factory()->make()->toArray();
        $data = ['code' => []];
        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $data['code'][] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        $data['code'][0] = $this->faker->randomFloat(5, $lottery->numbers_from, $lottery->numbers_to);

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code.0',
            ]);
    }

    public function test_array_size_fields_error()
    {
        $lottery = Lottery::inRandomOrder()->first();
        // $data = Code::factory()->make()->toArray();
        $data = ['code' => []];
        for ($i = 0; $i < $lottery->numbers_count + 1; $i++) {
            $data['code'][] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code',
            ]);
    }

    public function test_array_elements_min_size_fields_error()
    {
        $lottery = Lottery::inRandomOrder()->first();
        // $data = Code::factory()->make()->toArray();
        $data = ['code' => []];
        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $data['code'][] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        $data['code'][0] = $lottery->numbers_from - 1;

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code.0',
            ]);
    }

    public function test_array_elements_max_size_fields_error()
    {
        $lottery = Lottery::inRandomOrder()->first();
        // $data = Code::factory()->make()->toArray();
        $data = ['code' => []];
        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $data['code'][] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        $data['code'][0] = $lottery->numbers_to + 1;

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'code.0',
            ]);
    }

    public function test_array_elements_size_equals_to_min_fields_not_error()
    {
        $lottery = Lottery::inRandomOrder()->first();
        // $data = Code::factory()->make()->toArray();
        $data = ['code' => []];
        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $data['code'][] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        $data['code'][0] = $lottery->numbers_from;

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(201)
            ->assertValid([
                'code.0',
            ]);
    }

    public function test_array_elements_size_equals_to_max_fields_not_error()
    {
        $lottery = Lottery::inRandomOrder()->first();
        // $data = Code::factory()->make()->toArray();
        $data = ['code' => []];
        for ($i = 0; $i < $lottery->numbers_count; $i++) {
            $data['code'][] = $this->faker->numberBetween($lottery->numbers_from, $lottery->numbers_to);
        }

        $data['code'][0] = $lottery->numbers_to;

        $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

        $response->assertStatus(201)
            ->assertValid([
                'code.0',
            ]);
    }

    // public function test_array_elements_not_distinct_fields_error()
    // {
    //     $lottery = Lottery::inRandomOrder()->first();
    //     // $data = Code::factory()->make()->toArray();
    //     $data = ['code' => []];
    //     for ($i = 0; $i < $lottery->numbers_count; $i++) {
    //         $data['code'][] = $lottery->numbers_from;
    //     }

    //     $response = $this->postJson(Route('lottery.code.store', $lottery), $data);

    //     $response->assertStatus(422)
    //         ->assertJsonValidationErrors([
    //             'code.1',
    //         ]);
    // }
}
