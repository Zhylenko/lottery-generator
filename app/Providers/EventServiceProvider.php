<?php

namespace App\Providers;

use App\Models\Api\V1\Code;
use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode;
use App\Models\Api\V1\SpecialCode;
use App\Observers\Api\V1\CodeObserver;
use App\Observers\Api\V1\LotteryCodeCodeObserver;
use App\Observers\Api\V1\LotteryCodeLotteryObserver;
use App\Observers\Api\V1\LotteryCodeObserver;
use App\Observers\Api\V1\LotteryCodeSpecialCodeObserver;
use App\Observers\Api\V1\LotteryCodeSpecialLotteryObserver;
use App\Observers\Api\V1\LotteryObserver;
use App\Observers\Api\V1\SpecialCodeCodeObserver;
use App\Observers\Api\V1\SpecialCodeObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $observers = [
        Lottery::class => [
            LotteryObserver::class,
            LotteryCodeLotteryObserver::class,
            LotteryCodeSpecialLotteryObserver::class,
        ],
        Code::class => [
            CodeObserver::class,
            LotteryCodeCodeObserver::class,
            LotteryCodeSpecialCodeObserver::class,
            SpecialCodeCodeObserver::class,
        ],
        SpecialCode::class => [
            SpecialCodeObserver::class,
        ],
        LotteryCode::class => [
            LotteryCodeObserver::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
