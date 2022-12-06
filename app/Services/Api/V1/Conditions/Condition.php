<?php

namespace App\Services\Api\V1\Conditions;

use App\Contracts\Api\V1\Condition as ConditionContract;

abstract class Condition implements ConditionContract
{
    private ConditionContract $next;

    public function next(ConditionContract $condition): ConditionContract
    {
        $this->next = $condition;

        return $condition;
    }

    public function handle(mixed $data): bool
    {
        if (!empty($this->next)) {
            return $this->next->handle($data);
        }

        return true;
    }
}
