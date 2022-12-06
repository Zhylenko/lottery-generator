<?php

namespace Tests\Feature\Services\Api\V1\Conditions\Code;

use App\Contracts\Api\V1\Condition;
use App\Services\Api\V1\Conditions\Code\ConsecutiveNumbersCombinationsCondition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConsecutiveNumbersCombinationsConditionTest extends TestCase
{
    private Condition $condition;

    public function setUp(): void
    {
        parent::setUp();

        $this->condition = new ConsecutiveNumbersCombinationsCondition;
    }

    public function test_handle()
    {
        $code = [1, 3, 4, 5];

        $result = $this->condition->handle($code);

        $this->assertFalse($result);
    }

    public function test_handle_not_consecutive_numbers_combinations_code()
    {
        $code = [1, 3, 4, 6];

        $result = $this->condition->handle($code);

        $this->assertTrue($result);
    }
}
