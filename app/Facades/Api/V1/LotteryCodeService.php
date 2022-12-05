<?php

namespace App\Facades\Api\V1;

use Illuminate\Support\Facades\Facade;

class LotteryCodeService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LotteryCodeService';
    }
}
