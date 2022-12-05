<?php

namespace App\Services\Api\V1;

use App\Contracts\Api\V1\LotteryCode;
use App\Models\Api\V1\Lottery;

class LotteryCodeService extends CodeService implements LotteryCode
{
    public function generateLotteryCode(Lottery $lottery, bool $distinct = true): array
    {
        $code = parent::generate($lottery->numbers_count, $lottery->numbers_from, $lottery->numbers_to, $distinct);

        return $code;
    }
}
