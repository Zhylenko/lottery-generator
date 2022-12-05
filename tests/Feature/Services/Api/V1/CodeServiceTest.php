<?php

namespace Tests\Feature\Services\Api\V1;

use App\Contracts\Api\V1\Code;
use App\Services\Api\V1\CodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CodeServiceTest extends TestCase
{
    private Code $codeService;

    public function setUp(): void
    {
        parent::setUp();

        $this->codeService = new CodeService;
    }

    public function test_generate()
    {
        $code = $this->codeService->generate(10, 1, 10);

        $this->assertIsArray($code);
        $this->assertCount(10, $code);
    }

    public function test_generate_size_less_than_zero_return_empty_array()
    {
        $code = $this->codeService->generate(-1);

        $this->assertIsArray($code);
        $this->assertCount(0, $code);
    }

    public function test_generate_range()
    {
        $code = $this->codeService->generate(1, 1, 1);

        $this->assertIsArray($code);
        $this->assertCount(1, $code);
        $this->assertContains(1, $code);
        $this->assertNotContains(0, $code);
        $this->assertEquals([1], $code);
    }

    public function test_generate_distinct()
    {
        $code = $this->codeService->generate(2, 1, 1, true);

        $this->assertIsArray($code);
        $this->assertCount(1, $code);
        $this->assertContains(1, $code);
        $this->assertEquals([1], $code);
    }

    public function test_generate_not_distinct()
    {
        $code = $this->codeService->generate(2, 1, 1, false);

        $this->assertIsArray($code);
        $this->assertCount(2, $code);
        $this->assertEquals([1, 1], $code);
    }
}
