<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\Api\V1\LotteryCodeGeneratedExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ExportLotteryCodeGeneratedRequest;
use App\Http\Requests\Api\V1\GenerateLotteryCodeGeneratedRequest;
use App\Jobs\Api\V1\GenerateLotteryCodeJob;
use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode;
use Illuminate\Http\Request;

class LotteryCodeGeneratedController extends Controller
{
    public function generate(GenerateLotteryCodeGeneratedRequest $request)
    {
        LotteryCode::has('generated')
            ->whereRelation('lottery', 'id', $request->lottery)
            ->delete();

        for ($i = 0; $i < 100; $i++) {
            GenerateLotteryCodeJob::dispatch($request);
        }

        return 1;
    }

    public function export(ExportLotteryCodeGeneratedRequest $request, Lottery $lottery)
    {
        (new LotteryCodeGeneratedExport($lottery))->store('public/exports/Api/V1/LotteryCodeGenerated/lottery_code_generated.' . $request->extension);

        return 1;
    }
}
