<?php

namespace App\Contracts\Api\V1;

interface Code
{
    public function generate(int $size = 0, int $numbersFrom = 0, int $numbersTo = 0, bool $distinct = true): array;

    public function getAllCodeCombinations(array $code = []): array;

    public function getSizeCodeCombinations(array $code = [], int $size = 1): array;
}
