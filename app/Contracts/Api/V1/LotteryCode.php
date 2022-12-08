<?php

namespace App\Contracts\Api\V1;

use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode as LotteryCodeModel;

interface LotteryCode
{
    public function generateLotteryCode(Lottery $lottery, bool $distinct = true): array;

    public function storeLotteryCode(Lottery $lottery, array $code, bool $generated = true): LotteryCodeModel;
}
