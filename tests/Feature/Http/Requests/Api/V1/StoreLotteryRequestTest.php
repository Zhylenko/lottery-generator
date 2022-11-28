<?php

namespace Tests\Feature\Http\Requests\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreLotteryRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_empty_fields_error()
    {
        $data = [
            'name' => '',
            'numbers_count' => '',
            'numbers_from' => '',
            'numbers_to' => '',
        ];

        $response = $this->postJson(Route('lottery.store'), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'name',
                'numbers_count',
                'numbers_from',
                'numbers_to',
            ]);
    }

    public function test_numeric_fields_error()
    {
        $data = [
            'name' => $this->faker->numberBetween(1, 10),
        ];

        $response = $this->postJson(Route('lottery.store'), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'name',
            ]);
    }

    public function test_not_numeric_fields_error()
    {
        $data = [
            'numbers_count' => $this->faker->word(),
            'numbers_from' => $this->faker->word(),
            'numbers_to' => $this->faker->word(),
        ];

        $response = $this->postJson(Route('lottery.store'), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'numbers_count',
                'numbers_from',
                'numbers_to',
            ]);
    }

    public function test_not_unique_fields_error()
    {
        $randomLottery = Lottery::all()->random();
        $data = [
            'name' => $randomLottery->name,
        ];

        $response = $this->postJson(Route('lottery.store'), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'name',
            ]);
    }

    public function test_min_length_fields_error()
    {
        $data = [
            'name' => '',
            'numbers_count' => 0,
        ];

        $response = $this->postJson(Route('lottery.store'), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'name',
                'numbers_count',
            ]);
    }

    public function test_max_length_fields_error()
    {
        $data = [
            'name' => Str::random(256),
            'numbers_count' => 256,
        ];

        $response = $this->postJson(Route('lottery.store'), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'name',
                'numbers_count',
            ]);
    }

    public function test_numbers_from_field_greater_than_numbers_to_field_error()
    {
        $from = 36;
        $to = 35;

        $data = [
            'numbers_from' => $from,
            'numbers_to' => $to,
        ];

        $response = $this->postJson(Route('lottery.store'), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'numbers_from',
                'numbers_to',
            ]);
    }

    public function test_numbers_from_field_equals_to_numbers_to_field_not_error()
    {
        $from = 35;
        $to = 35;

        $data = [
            'numbers_from' => $from,
            'numbers_to' => $to,
        ];

        $response = $this->postJson(Route('lottery.store'), $data);

        $response->assertStatus(422)
            ->assertValid([
                'numbers_from',
                'numbers_to',
            ]);
    }

    public function test_numbers_from_field_less_then_numbers_to_field_not_error()
    {
        $from = 34;
        $to = 35;

        $data = [
            'numbers_from' => $from,
            'numbers_to' => $to,
        ];

        $response = $this->postJson(Route('lottery.store'), $data);

        $response->assertStatus(422)
            ->assertValid([
                'numbers_from',
                'numbers_to',
            ]);
    }
}
