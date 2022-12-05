<?php

namespace Tests\Feature\Http\Requests\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenerateLotteryCodeRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_empty_fields_error()
    {
        $data = [
            'lottery' => '',
            'exclude_code_combinations' => [],
            'include_sets_of_consecutive_2_numbers_combinations' => '',
            'include_special_codes_combinations' => '',
            'exclude_sets_of_consecutive_numbers_combinations' => '',
            'exclude_generated_sets_of_consecutive_numbers_combinations' => '',
            'count' => '',
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'lottery',
                'exclude_code_combinations',
                'include_sets_of_consecutive_2_numbers_combinations',
                'include_special_codes_combinations',
                'exclude_sets_of_consecutive_numbers_combinations',
                'exclude_generated_sets_of_consecutive_numbers_combinations',
                'count',
            ]);
    }

    public function test_not_exists_fields_error()
    {
        $data = [
            'lottery' => 0,
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'lottery',
            ]);
    }

    public function test_not_boolean_fields_error()
    {
        $data = [
            'include_sets_of_consecutive_2_numbers_combinations' => $this->faker->word(),
            'include_special_codes_combinations' => $this->faker->word(),
            'exclude_sets_of_consecutive_numbers_combinations' => $this->faker->word(),
            'exclude_generated_sets_of_consecutive_numbers_combinations' => $this->faker->word(),
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'include_sets_of_consecutive_2_numbers_combinations',
                'include_special_codes_combinations',
                'exclude_sets_of_consecutive_numbers_combinations',
                'exclude_generated_sets_of_consecutive_numbers_combinations',
            ]);
    }

    public function test_not_integer_fields_error()
    {
        $data = [
            'lottery' => $this->faker->word(),
            'exclude_code_combinations' => [
                [
                    'numbers' => $this->faker->word(),
                    'count' => $this->faker->word(),
                    'from' => $this->faker->word(),
                    'to' => $this->faker->word(),
                ],
            ],
            'count' => $this->faker->word(),
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'lottery',
                'exclude_code_combinations.0.numbers',
                'exclude_code_combinations.0.count',
                'exclude_code_combinations.0.from',
                'exclude_code_combinations.0.to',
                'count',
            ]);
    }

    public function test_float_integer_fields_error()
    {
        $data = [
            'lottery' => $this->faker->randomFloat(5, -10, 10),
            'exclude_code_combinations' => [
                [
                    'numbers' => $this->faker->randomFloat(5, -10, 10),
                    'count' => $this->faker->randomFloat(5, -10, 10),
                    'from' => $this->faker->randomFloat(5, -10, 10),
                    'to' => $this->faker->randomFloat(5, -10, 10),
                ],
            ],
            'count' => $this->faker->randomFloat(5, -10, 10),
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'lottery',
                'exclude_code_combinations.0.numbers',
                'exclude_code_combinations.0.count',
                'exclude_code_combinations.0.from',
                'exclude_code_combinations.0.to',
                'count',
            ]);
    }

    public function test_min_length_fields_error()
    {
        $data = [
            'exclude_code_combinations' => [
                [
                    'numbers' => 0,
                    'count' => -1,
                    'from' => 0,
                    'to' => 0,
                ],
            ],
            'count' => 0,
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'exclude_code_combinations.0.numbers',
                'exclude_code_combinations.0.count',
                'exclude_code_combinations.0.from',
                'exclude_code_combinations.0.to',
                'count',
            ]);
    }

    public function test_not_array_fields_error()
    {
        $data = [
            'exclude_code_combinations' => $this->faker->word(),
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'exclude_code_combinations',
            ]);
    }

    public function test_array_not_array_fields_error()
    {
        $data = [
            'exclude_code_combinations' => [
                $this->faker->word(),
            ],
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'exclude_code_combinations.0',
            ]);
    }

    public function test_exclude_code_combinations_from_field_greater_than_exclude_code_combinations_to_field_error()
    {
        $data = [
            'exclude_code_combinations' => [
                [
                    'from' => 36,
                    'to' => 35,
                ],
            ],
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'exclude_code_combinations.0.from',
                'exclude_code_combinations.0.to',
            ]);
    }

    public function test_exclude_code_combinations_from_field_equals_to_exclude_code_combinations_to_field_not_error()
    {
        $data = [
            'exclude_code_combinations' => [
                [
                    'from' => 35,
                    'to' => 35,
                ],
            ],
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertValid([
                'exclude_code_combinations.0.from',
                'exclude_code_combinations.0.to',
            ]);
    }

    public function test_exclude_code_combinations_from_field_less_then_exclude_code_combinations_to_field_not_error()
    {
        $data = [
            'exclude_code_combinations' => [
                [
                    'from' => 34,
                    'to' => 35,
                ],
            ],
        ];

        $response = $this->postJson(Route('lottery.code.generated.generate'), $data);

        $response->assertStatus(422)
            ->assertValid([
                'exclude_code_combinations.0.from',
                'exclude_code_combinations.0.to',
            ]);
    }
}
