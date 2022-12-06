<?php

namespace App\Contracts\Api\V1;

use App\Models\Api\V1\Lottery;

interface LotteryCode
{
    public function generateLotteryCode(Lottery $lottery, bool $distinct = true): array;
}
