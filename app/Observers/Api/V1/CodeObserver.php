<?php

namespace App\Observers\Api\V1;

use App\Models\Api\V1\Code;
use Illuminate\Support\Facades\Cache;

class CodeObserver
{
    /**
     * Handle the Code "creating" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function creating(Code $code)
    {
        $code->code = $this->formatCode($code->code);
    }

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
     * Handle the Code "updating" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function updating(Code $code)
    {
        $code->code = $this->formatCode($code->code);
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
     * Handle the Code "deleted" event.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return void
     */
    public function deleted(Code $code)
    {
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

    private function clearCache()
    {
        Cache::tags(['codes'])->flush();
    }

    private function formatCode(array $code = [])
    {
        return array_map('intval', $code);
    }
}
