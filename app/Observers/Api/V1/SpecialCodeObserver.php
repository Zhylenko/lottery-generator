<?php

namespace App\Observers\Api\V1;

use App\Models\Api\V1\SpecialCode;
use Illuminate\Support\Facades\Cache;

class SpecialCodeObserver
{
    /**
     * Handle the SpecialCode "created" event.
     *
     * @param  \App\Models\Api\V1\SpecialCode  $specialCode
     * @return void
     */
    public function created(SpecialCode $specialCode)
    {
        $this->clearCache();
    }

    /**
     * Handle the SpecialCode "updated" event.
     *
     * @param  \App\Models\Api\V1\SpecialCode  $specialCode
     * @return void
     */
    public function updated(SpecialCode $specialCode)
    {
        $this->clearCache();
    }

    /**
     * Handle the SpecialCode "deleted" event.
     *
     * @param  \App\Models\Api\V1\SpecialCode  $specialCode
     * @return void
     */
    public function deleted(SpecialCode $specialCode)
    {
        $this->deleteSpecialCodeCode($specialCode);

        $this->clearCache();
    }

    /**
     * Handle the SpecialCode "restored" event.
     *
     * @param  \App\Models\Api\V1\SpecialCode  $specialCode
     * @return void
     */
    public function restored(SpecialCode $specialCode)
    {
        $this->clearCache();
    }

    /**
     * Handle the SpecialCode "force deleted" event.
     *
     * @param  \App\Models\Api\V1\SpecialCode  $specialCode
     * @return void
     */
    public function forceDeleted(SpecialCode $specialCode)
    {
        $this->deleteSpecialCodeCode($specialCode);

        $this->clearCache();
    }

    public function deleteSpecialCodeCode(SpecialCode $specialCode)
    {
        return $specialCode->code()->delete();
    }

    public function clearCache()
    {
        Cache::tags(['codes', 'special'])->flush();
    }
}
