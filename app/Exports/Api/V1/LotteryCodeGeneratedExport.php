<?php

namespace App\Exports\Api\V1;

use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCodeGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class LotteryCodeGeneratedExport implements FromQuery, WithMapping, ShouldQueue
{
    use Exportable;

    private Lottery $lottery;

    public function __construct(Lottery $lottery)
    {
        $this->lottery = $lottery;
    }

    public function query()
    {
        $lottery = $this->lottery;

        return LotteryCodeGenerated::query()
            ->whereRelation('lotteryCode.lottery', 'id', $lottery->id);
    }

    public function map($lotteryCodeGenerated): array
    {
        $code = new Collection($lotteryCodeGenerated->lotteryCode->code->code);

        return [
            $code->implode(','),
        ];
    }
}
