<?php

namespace App\Observers\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Support\Facades\Cache;

class LotteryObserver
{
    /**
     * Handle the Lottery "created" event.
     *
     * @param  \App\Models\Api\V1\Lottery  $lottery
     * @return void
     */
    public function created(Lottery $lottery)
    {
        $this->clearCache();
    }

    /**
     * Handle the Lottery "updated" event.
     *
     * @param  \App\Models\Api\V1\Lottery  $lottery
     * @return void
     */
    public function updated(Lottery $lottery)
    {
        $this->clearCache();
    }

    /**
     * Handle the Lottery "updating" event.
     *
     * @param  \App\Models\Api\V1\Lottery  $lottery
     * @return void
     */
    public function updating(Lottery $lottery)
    {
        $oldLottery = Lottery::findOrFail($lottery->id);

        if ($lottery->numbers_count != $oldLottery->numbers_count || $lottery->numbers_from != $oldLottery->numbers_from || $lottery->numbers_to != $oldLottery->numbers_to) {
        }
    }

    /**
     * Handle the Lottery "deleted" event.
     *
     * @param  \App\Models\Api\V1\Lottery  $lottery
     * @return void
     */
    public function deleted(Lottery $lottery)
    {
        $this->clearCache();
    }

    /**
     * Handle the Lottery "restored" event.
     *
     * @param  \App\Models\Api\V1\Lottery  $lottery
     * @return void
     */
    public function restored(Lottery $lottery)
    {
        $this->clearCache();
    }

    /**
     * Handle the Lottery "force deleted" event.
     *
     * @param  \App\Models\Api\V1\Lottery  $lottery
     * @return void
     */
    public function forceDeleted(Lottery $lottery)
    {
        $this->clearCache();
    }

    private function clearCache()
    {
        Cache::tags(['lotteries'])->flush();
    }
}
