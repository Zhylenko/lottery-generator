<?php

namespace Tests\Feature\Http\Middleware\Api\V1;

use App\Http\Middleware\Api\V1\FormatPageNumberField;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class FormatPageNumberFieldTest extends TestCase
{
    use WithFaker;

    private $middleware, $page;

    public function setUp(): void
    {
        parent::setUp();

        $this->middleware = new FormatPageNumberField;
    }

    public function test_request_without_page_field_formatted()
    {
        $request = new Request();

        $request->merge([]);

        $this->middleware->handle($request, function ($nextRequest) {
            $this->assertFalse($nextRequest->has('page'));
            $this->assertNull($nextRequest->page);
        });
    }

    public function test_request_with_empty_page_field_formatted()
    {
        $request = new Request();
        $this->page = '';

        $request->merge([
            'page' => $this->page,
        ]);

        $this->middleware->handle($request, function ($nextRequest) {
            $this->assertEquals(1, $nextRequest->page);
        });
    }

    public function test_request_with_zero_page_field_formatted()
    {
        $request = new Request();
        $this->page = 0;

        $request->merge([
            'page' => $this->page,
        ]);

        $this->middleware->handle($request, function ($nextRequest) {
            $this->assertEquals(1, $nextRequest->page);
        });
    }

    public function test_request_with_positive_numeric_page_field_formatted()
    {
        $request = new Request();
        $this->page = $this->faker->numberBetween(1, 10);

        $request->merge([
            'page' => $this->page,
        ]);

        $this->middleware->handle($request, function ($nextRequest) {
            $this->assertEquals($this->page, $nextRequest->page);
        });
    }

    public function test_request_with_negative_numeric_page_field_formatted()
    {
        $request = new Request();
        $this->page = $this->faker->numberBetween(-10, -1);

        $request->merge([
            'page' => $this->page,
        ]);

        $this->middleware->handle($request, function ($nextRequest) {
            $this->assertEquals(abs($this->page), $nextRequest->page);
        });
    }

    public function test_request_with_float_page_field_formatted()
    {
        $request = new Request();
        $this->page = $this->faker->randomFloat(5, -10, 10);

        $request->merge([
            'page' => $this->page,
        ]);

        $this->middleware->handle($request, function ($nextRequest) {
            $this->assertEquals(1, $nextRequest->page);
        });
    }

    public function test_request_with_text_page_field_formatted()
    {
        $request = new Request();
        $this->page = $this->faker->word();

        $request->merge([
            'page' => $this->page,
        ]);

        $this->middleware->handle($request, function ($nextRequest) {
            $this->assertEquals(1, $nextRequest->page);
        });
    }

    public function test_request_with_numeric_text_page_field_formatted()
    {
        $request = new Request();
        $this->page = $this->faker->numberBetween(-10, 10) . $this->faker->word();

        $request->merge([
            'page' => $this->page,
        ]);

        $this->middleware->handle($request, function ($nextRequest) {
            $this->assertEquals(1, $nextRequest->page);
        });
    }
}
