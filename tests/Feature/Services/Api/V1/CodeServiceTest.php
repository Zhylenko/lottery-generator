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

    public function test_get_all_code_combinations()
    {
        $code = [1, 2, 3];
        $combinations = $this->codeService->getAllCodeCombinations($code);

        $this->assertEquals([
            1 => [
                [1],
                [2],
                [3],
            ],
            2 => [
                [1, 2],
                [1, 3],
                [2, 3],
            ],
            3 => [
                [1, 2, 3],
            ],
        ], $combinations);
    }

    public function test_get_all_not_distinct_code_combinations()
    {
        $code = [1, 2, 1];
        $combinations = $this->codeService->getAllCodeCombinations($code);

        $this->assertEquals([
            1 => [
                [1],
                [2],
            ],
            2 => [
                [1, 1],
                [1, 2],
            ],
            3 => [
                [1, 1, 2],
            ],
        ], $combinations);
    }

    public function test_get_size_code_combinations()
    {
        $code = [1, 2, 3];
        $combinations = $this->codeService->getSizeCodeCombinations($code, 1);

        $this->assertEquals([
            [1],
            [2],
            [3],
        ], $combinations);
    }

    public function test_get_size_not_distinct_code_combinations()
    {
        $code = [1, 2, 1];
        $combinations = $this->codeService->getSizeCodeCombinations($code, 1);

        $this->assertEquals([
            [1],
            [2],
        ], $combinations);
    }

    public function test_get_size_less_than_one_empty_combinations()
    {
        $code = [1, 2, 3];
        $combinations = $this->codeService->getSizeCodeCombinations($code, 0);

        $this->assertEquals([], $combinations);
    }

    public function test_get_size_greater_than_code_numbers_count_empty_combinations()
    {
        $code = [1, 2, 3];
        $combinations = $this->codeService->getSizeCodeCombinations($code, 4);

        $this->assertEquals([], $combinations);
    }
}
