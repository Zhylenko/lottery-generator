<?php

namespace App\Providers\Api\V1;

use App\Services\Api\V1\CodeService;
use Illuminate\Support\ServiceProvider;

class CodeServiceProvider extends ServiceProvider
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
        $this->app->bind('CodeService', CodeService::class);
    }
}
