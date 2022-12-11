<?php

namespace App\Services\Api\V1\Conditions\Code;

use App\Models\Api\V1\Code;
use App\Models\Api\V1\Lottery;
use App\Services\Api\V1\Conditions\Condition;
use Illuminate\Support\Collection;

class CodeCombinationsCondition extends Condition
{
    private Lottery $lottery;

    private
        $numbers,
        $count,
        $from,
        $to;

    public function __construct(Lottery $lottery, int $numbers, int $count, int $from, int $to)
    {
        $this->lottery = $lottery;

        $this->numbers = $numbers;
        $this->count = $count;
        $this->from = $from;
        $this->to = $to;
    }

    public function handle($code): bool
    {
        $lottery = $this->lottery;

        $numbers = $this->numbers;
        $count = $this->count;
        $from = $this->from;
        $to = $this->to;

        $fromRecord = Code::whereRelation('lotteries', 'lottery_id', $lottery->id)
            ->where('id', '>=', $from)
            ->where('id', '<=', $to)
            ->orderBy('id', 'desc')
            ->offset($count - 1)
            ->first();

        $from = $fromRecord ? $fromRecord->id : $from;

        $codeCollection = new Collection($code);
        $codeCombinations = \CodeService::getSizeCodeCombinations($codeCollection->values()->all(), $numbers);

        foreach ($codeCombinations as $codeCombination) {
            $result = Code::whereRelation('lotteries', 'lottery_id', $lottery->id)
                ->where('id', '>=', $from)
                ->where('id', '<=', $to)
                ->whereJsonContains('code', $codeCombination)
                ->exists();

            if ($result)
                return false;
        }

        return parent::handle($code);
    }
}
