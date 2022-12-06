<?php

namespace Tests\Feature\Services\Api\V1\Conditions\Code;

use App\Contracts\Api\V1\Condition;
use App\Models\Api\V1\Code;
use App\Models\Api\V1\SpecialCode;
use App\Services\Api\V1\Conditions\Code\SpecialCodeCondition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SpecialCodeConditionTest extends TestCase
{
    private Condition $condition;

    public function setUp(): void
    {
        parent::setUp();

        $this->condition = new SpecialCodeCondition;
    }

    public function test_handle()
    {
        $specialCode = SpecialCode::has('code')->inRandomOrder()->first();
        $specialCodeCode = $specialCode->code;

        $result = $this->condition->handle($specialCodeCode->code);

        $this->assertFalse($result);
    }

    public function test_handle_not_special_code()
    {
        $code = Code::doesntHave('special')->inRandomOrder()->first();

        $result = $this->condition->handle($code->code);

        $this->assertTrue($result);
    }
}
