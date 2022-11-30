<?php

namespace App\Observers\Api\V1;

use App\Models\Api\V1\Code;
use Illuminate\Support\Facades\Cache;

class LotteryCodeCodeObserver
{
    /**
     * Handle the Code "created" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function created(Code $code)
    {
        $this->clearCache();
    }

    /**
     * Handle the Code "updated" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function updated(Code $code)
    {
        $this->clearCache();
    }

    /**
     * Handle the Code "updating" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function updating(Code $code)
    {
        if ($code->lotteries()->exists()) {
            $codeLottery = $code->lotteries()->first();

            if (count($code->code) != $codeLottery->numbers_count || min($code->code) < $codeLottery->numbers_from || max($code->code) > $codeLottery->numbers_to)
                $this->detachLotteryCode($code);
        }
    }


    /**
     * Handle the Code "deleted" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function deleted(Code $code)
    {
        if ($code->lotteries()->exists())
            $this->detachLotteryCode($code);

        $this->clearCache();
    }

    /**
     * Handle the Code "restored" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function restored(Code $code)
    {
        $this->clearCache();
    }

    /**
     * Handle the Code "force deleted" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function forceDeleted(Code $code)
    {
        $this->clearCache();
    }

    public function detachLotteryCode(Code $code)
    {
        $lottery = $code->lotteries()->first();

        return $lottery->codes()->detach($code);
    }

    private function clearCache()
    {
        Cache::tags(['lotteries', 'codes'])->flush();
    }
}
