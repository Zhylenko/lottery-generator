<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GenerateLotteryCodeRequest;
use Illuminate\Http\Request;

class LotteryCodeGeneratedController extends Controller
{
    public function generate(GenerateLotteryCodeRequest $request)
    {
        return 1;
    }
}
