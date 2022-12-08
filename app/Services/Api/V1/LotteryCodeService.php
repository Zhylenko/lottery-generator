<?php

namespace App\Services\Api\V1;

use App\Contracts\Api\V1\LotteryCode;
use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode as LotteryCodeModel;

class LotteryCodeService extends CodeService implements LotteryCode
{
    public function generateLotteryCode(Lottery $lottery, bool $distinct = true): array
    {
        $code = parent::generate($lottery->numbers_count, $lottery->numbers_from, $lottery->numbers_to, $distinct);

        return $code;
    }

    public function storeLotteryCode(Lottery $lottery, array $code, bool $generated = true): LotteryCodeModel
    {
        $lotteryCode = $lottery->codes()->create([
            'code' => $code,
        ]);

        $lotteryCode = LotteryCodeModel::whereRelation('lottery', 'id', $lottery->id)->whereRelation('code', 'id', $lotteryCode->id)->first();

        if ($generated && $lotteryCode)
            $lotteryCode->generated()->create();

        return $lotteryCode;
    }
}
