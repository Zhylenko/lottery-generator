<?php

namespace App\Contracts\Api\V1;

interface Condition
{
    public function next(Condition $condition): Condition;
    public function handle(mixed $data): bool;
}
