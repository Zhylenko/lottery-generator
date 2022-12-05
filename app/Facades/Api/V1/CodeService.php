<?php

namespace App\Facades\Api\V1;

use Illuminate\Support\Facades\Facade;

class CodeService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CodeService';
    }
}
