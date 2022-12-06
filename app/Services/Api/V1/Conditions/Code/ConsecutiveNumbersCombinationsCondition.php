<?php

namespace App\Services\Api\V1\Conditions\Code;

use App\Services\Api\V1\Conditions\Condition;
use Illuminate\Support\Collection;

class ConsecutiveNumbersCombinationsCondition extends Condition
{
    public function handle($code): bool
    {
        $codeCollection = new Collection($code);

        for ($i = 0; $i < $codeCollection->count(); $i++) {
            if ($codeCollection->contains((int) $codeCollection->get($i) + 1) && $codeCollection->contains((int) $codeCollection->get($i) + 2))
                return false;
        }

        return parent::handle($code);
    }
}
