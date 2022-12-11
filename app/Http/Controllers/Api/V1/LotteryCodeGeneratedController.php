<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GenerateLotteryCodeGeneratedRequest;
use App\Jobs\Api\V1\GenerateLotteryCodeJob;
use Illuminate\Http\Request;

class LotteryCodeGeneratedController extends Controller
{
    public function generate(GenerateLotteryCodeGeneratedRequest $request)
    {
        GenerateLotteryCodeJob::dispatch($request);

        return 1;
    }
}
