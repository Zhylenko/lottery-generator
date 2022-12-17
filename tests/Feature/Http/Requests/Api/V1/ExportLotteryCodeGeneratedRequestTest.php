<?php

namespace Tests\Feature\Http\Requests\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class ExportLotteryCodeGeneratedRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Collection $extensions;

    public function setUp(): void
    {
        parent::setUp();

        $this->extensions = new Collection([
            'xlsx',
            // 'xlsm',
            // 'xltx',
            // 'xltm',
            // 'xls',
            // 'xlt',
            // 'ods',
            // 'ots',
            // 'slk',
            'xml',
            // 'gnumeric',
            // 'htm',
            // 'html',
            'csv',
            // 'tsv',
            // 'pdf',
        ]);
    }

    public function test_empty_fields_error()
    {
        $lottery = Lottery::inRandomOrder()->first();

        $data = [
            'extension' => '',
        ];

        $response = $this->postJson(Route('lottery.code.generated.export', $lottery), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'extension',
            ]);
    }

    public function test_not_string_fields_error()
    {
        $lottery = Lottery::inRandomOrder()->first();

        $data = [
            'extension' => $this->faker->numberBetween(0, 100),
        ];

        $response = $this->postJson(Route('lottery.code.generated.export', $lottery), $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'extension',
            ]);
    }

    public function test_in_fields_not_error()
    {
        $lottery = Lottery::inRandomOrder()->first();
        $extensions = $this->extensions;

        $data = [
            'extension' => $extensions->random(),
        ];

        $response = $this->postJson(Route('lottery.code.generated.export', $lottery), $data);

        $response->assertStatus(200)
            ->assertValid([
                'extension',
            ]);
    }
}
