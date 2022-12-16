<?php

namespace App\Services\Api\V1\Conditions\Code;

use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCodeSpecial;
use App\Services\Api\V1\Conditions\Condition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SpecialCodeCondition extends Condition
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

        $result = LotteryCodeSpecial::whereHas('lotteryCode', function (Builder $query) use ($codeCollection, $lottery) {
            $query
                ->whereRelation('lottery', 'id', $lottery->id)
                ->whereHas('code', function (Builder $query) use ($codeCollection) {
                    $query
                        ->whereJsonLength('code', $codeCollection->count())
                        ->whereJsonContains('code', $codeCollection->values()->all());
                });
        })->exists();

        if ($result)
            return false;

        return parent::handle($code);
    }
}
