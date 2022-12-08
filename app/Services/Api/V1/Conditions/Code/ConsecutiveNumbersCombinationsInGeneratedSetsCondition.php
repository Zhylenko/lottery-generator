<?php

namespace App\Services\Api\V1\Conditions\Code;

use App\Models\Api\V1\LotteryCode;
use App\Services\Api\V1\Conditions\Condition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ConsecutiveNumbersCombinationsInGeneratedSetsCondition extends Condition
{
    public function handle($code): bool
    {
        $codeCollection = new Collection($code);
        $codeCombinations = \CodeService::getSizeCodeCombinations($codeCollection->values()->all(), 4);

        foreach ($codeCombinations as $codeCombination) {
            $result = LotteryCode::has('generated')->whereHas('code', function (Builder $query) use ($codeCombination) {
                $query->whereJsonContains('code', $codeCombination);
            })->exists();

            if ($result)
                return false;
        }

        return parent::handle($code);
    }
}
