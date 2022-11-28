<?php

namespace Tests\Feature\Http\Requests\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UpdateLotteryRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_empty_fields_error()
    {
        $lottery = Lottery::all()->random();

        $data = [
            'name' => '',
            'numbers_count' => '',
            'numbers_from' => '',
            'numbers_to' => '',
        ];

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

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
        $lottery = Lottery::all()->random();

        $data = [
            'name' => $this->faker->numberBetween(1, 10),
        ];

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'name',
            ]);
    }

    public function test_not_numeric_fields_error()
    {
        $lottery = Lottery::all()->random();

        $data = [
            'numbers_count' => $this->faker->word(),
            'numbers_from' => $this->faker->word(),
            'numbers_to' => $this->faker->word(),
        ];

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'numbers_count',
                'numbers_from',
                'numbers_to',
            ]);
    }

    public function test_not_unique_fields_error()
    {
        $lottery = Lottery::all()->random();

        $randomLottery = Lottery::where('id', '!=', $lottery->id)->get()->random();
        $data = [
            'name' => $randomLottery->name,
        ];

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'name',
            ]);
    }

    public function test_unique_fields_without_changes_not_error()
    {
        $lottery = Lottery::all()->random();

        $data = [
            'name' => $lottery->name,
        ];

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

        $response->assertStatus(422)
            ->assertValid([
                'name',
            ]);
    }

    public function test_min_length_fields_error()
    {
        $lottery = Lottery::all()->random();

        $data = [
            'name' => '',
            'numbers_count' => 0,
        ];

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'name',
                'numbers_count',
            ]);
    }

    public function test_max_length_fields_error()
    {
        $lottery = Lottery::all()->random();

        $data = [
            'name' => Str::random(256),
            'numbers_count' => 256,
        ];

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'name',
                'numbers_count',
            ]);
    }

    public function test_numbers_from_field_greater_than_numbers_to_field_error()
    {
        $lottery = Lottery::all()->random();

        $from = 36;
        $to = 35;

        $data = [
            'numbers_from' => $from,
            'numbers_to' => $to,
        ];

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

        $response->assertStatus(422)
            ->assertInvalid([
                'numbers_from',
                'numbers_to',
            ]);
    }

    public function test_numbers_from_field_equals_to_numbers_to_field_not_error()
    {
        $lottery = Lottery::all()->random();

        $from = 35;
        $to = 35;

        $data = [
            'numbers_from' => $from,
            'numbers_to' => $to,
        ];

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

        $response->assertStatus(422)
            ->assertValid([
                'numbers_from',
                'numbers_to',
            ]);
    }

    public function test_numbers_from_field_less_then_numbers_to_field_not_error()
    {
        $lottery = Lottery::all()->random();

        $from = 34;
        $to = 35;

        $data = [
            'numbers_from' => $from,
            'numbers_to' => $to,
        ];

        $response = $this->putJson(Route('lottery.update', $lottery), $data);

        $response->assertStatus(422)
            ->assertValid([
                'numbers_from',
                'numbers_to',
            ]);
    }
}
