<?php

namespace App\Observers\Api\V1;

use App\Models\Api\V1\LotteryCode;

class LotteryCodeObserver
{
    /**
     * Handle the LotteryCode "created" event.
     *
     * @param  \App\Models\Api\V1\LotteryCode  $lotteryCode
     * @return void
     */
    public function created(LotteryCode $lotteryCode)
    {
        //
    }

    /**
     * Handle the LotteryCode "updated" event.
     *
     * @param  \App\Models\Api\V1\LotteryCode  $lotteryCode
     * @return void
     */
    public function updated(LotteryCode $lotteryCode)
    {
        //
    }

    /**
     * Handle the LotteryCode "deleted" event.
     *
     * @param  \App\Models\Api\V1\LotteryCode  $lotteryCode
     * @return void
     */
    public function deleted(LotteryCode $lotteryCode)
    {
        $this->deleteGenerated($lotteryCode);
    }

    /**
     * Handle the LotteryCode "restored" event.
     *
     * @param  \App\Models\Api\V1\LotteryCode  $lotteryCode
     * @return void
     */
    public function restored(LotteryCode $lotteryCode)
    {
        //
    }

    /**
     * Handle the LotteryCode "force deleted" event.
     *
     * @param  \App\Models\Api\V1\LotteryCode  $lotteryCode
     * @return void
     */
    public function forceDeleted(LotteryCode $lotteryCode)
    {
        $this->deleteGenerated($lotteryCode);
    }

    public function deleteGenerated(LotteryCode $lotteryCode)
    {
        return $lotteryCode->generated()->delete();
    }
}
