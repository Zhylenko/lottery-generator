<?php

namespace App\Services\Api\V1\Conditions\Code;

use App\Models\Api\V1\LotteryCodeSpecial;
use App\Services\Api\V1\Conditions\Condition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SpecialCodeCondition extends Condition
{
    public function handle($code): bool
    {
        $codeCollection = new Collection($code);

        $result = LotteryCodeSpecial::whereHas('lotteryCode', function (Builder $query) use ($codeCollection) {
            $query->whereHas('code', function (Builder $query) use ($codeCollection) {
                $query->whereJsonLength('code', $codeCollection->count())
                    ->whereJsonContains('code', $codeCollection->values()->all());
            });
        })->exists();

        if ($result)
            return false;

        return parent::handle($code);
    }
}
