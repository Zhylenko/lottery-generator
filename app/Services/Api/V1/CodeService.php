<?php

namespace App\Services\Api\V1;

use App\Contracts\Api\V1\Code;
use Illuminate\Support\Collection;

class CodeService implements Code
{
    private $combinations = [];

    public function generate(int $size = 0, int $numbersFrom = 0, int $numbersTo = 0, bool $distinct = true): array
    {
        if ($size < 0) {
            return [];
        }

        $code = new Collection;
        $numbersCollection = $this->getRange($numbersFrom, $numbersTo);

        for ($i = 0; $i < $size; $i++) {
            if ($numbersCollection->isEmpty())
                break;

            $randomNumber = $numbersCollection->random();
            $code->push($randomNumber);

            if ($distinct == true) {
                $numbersCollection->forget($numbersCollection->search($randomNumber));
            }
        }

        return $code->sort()->values()->all();
    }

    public function getAllCodeCombinations(array $code = []): array
    {
        $codeCollection = (new Collection($code))->sort();
        $combinations = new Collection;

        for ($i = 1; $i <= $codeCollection->count(); $i++) {
            $combinations->put($i, $this->getSizeCodeCombinations($codeCollection->values()->all(), $i));
        }

        return $combinations->unique()->all();
    }

    public function getSizeCodeCombinations(array $code = [], int $size = 1): array
    {
        $codeCollection = (new Collection($code))->sort();

        if ($size < 1 || $size > $codeCollection->count())
            return [];

        $this->combinations = [];

        $this->generateSizeCombinations($codeCollection->values()->all(), $size);

        return (new Collection($this->combinations))->unique()->values()->all();
    }

    protected function getRange(int $numbersFrom = 0, int $numbersTo = 0): Collection
    {
        return Collection::range($numbersFrom, $numbersTo);
    }

    protected function generateSizeCombinations($code, $size, $data = [], $start = 0, $index = 0): void
    {
        if ($index == $size) {
            $combination = [];

            for ($j = 0; $j < $size; $j++)
                $combination[] = $data[$j];

            $this->combinations[] = $combination;

            return;
        }

        $end = (new Collection($code))->count() - 1;

        for ($i = $start; ($i <= $end) && (($end - $i + 1) >= ($size - $index)); $i++) {
            $data[$index] = $code[$i];
            $this->generateSizeCombinations($code, $size, $data, $i + 1, $index + 1);
        }
    }
}
