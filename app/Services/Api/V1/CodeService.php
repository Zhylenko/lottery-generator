<?php

namespace App\Services\Api\V1;

use App\Contracts\Api\V1\Code;
use Illuminate\Support\Collection;

class CodeService implements Code
{
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

    protected function getRange(int $numbersFrom = 0, int $numbersTo = 0): Collection
    {
        return Collection::range($numbersFrom, $numbersTo);
    }
}
