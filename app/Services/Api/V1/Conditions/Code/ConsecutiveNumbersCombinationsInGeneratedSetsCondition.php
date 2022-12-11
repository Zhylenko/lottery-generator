<?php

namespace App\Services\Api\V1\Conditions\Code;

use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode;
use App\Services\Api\V1\Conditions\Condition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ConsecutiveNumbersCombinationsInGeneratedSetsCondition extends Condition
{
    private Lottery $lottery;

    public function __construct(Lottery $lottery)
    {
        $this->lottery = $lottery;
    }

    public function handle($code): bool
    {
        $lottery = $this->lottery;

        $codeCollection = new Collection($code);
        $codeCombinations = \CodeService::getSizeCodeCombinations($codeCollection->values()->all(), 4);

        foreach ($codeCombinations as $codeCombination) {
            $result = LotteryCode::has('generated')
                ->whereRelation('lottery', 'id', $lottery->id)
                ->whereHas('code', function (Builder $query) use ($codeCombination) {
                    $query->whereJsonContains('code', $codeCombination);
                })->exists();

            if ($result)
                return false;
        }

        return parent::handle($code);
    }
}
