<?php

namespace App\Observers\Api\V1;

use App\Models\Api\V1\Code;
use App\Models\Api\V1\SpecialCode;
use Illuminate\Support\Facades\Cache;

class SpecialCodeCodeObserver
{
    /**
     * Handle the Code "created" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function created(Code $code)
    {
        if ($code->special()->exists())
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
        if ($code->special()->exists())
            $this->clearCache();
    }

    /**
     * Handle the Code "deleted" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function deleted(Code $code)
    {
        if ($code->special()->exists()) {
            $this->deleteCodeSpecial($code->special);
            $this->clearCache();
        }
    }

    /**
     * Handle the Code "restored" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function restored(Code $code)
    {
        if ($code->special()->exists())
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
        if ($code->special()->exists()) {
            $this->deleteCodeSpecial($code->special);
            $this->clearCache();
        }
    }

    public function deleteCodeSpecial(SpecialCode $codeSpecial)
    {
        return $codeSpecial->delete();
    }

    public function clearCache()
    {
        Cache::tags(['codes', 'special'])->flush();
    }
}
