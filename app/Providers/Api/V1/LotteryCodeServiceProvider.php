<?php

namespace App\Providers\Api\V1;

use App\Services\Api\V1\LotteryCodeService;
use Illuminate\Support\ServiceProvider;

class LotteryCodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('LotteryCodeService', LotteryCodeService::class);
    }
}
