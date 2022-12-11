<?php

namespace App\Jobs\Api\V1;

use App\Http\Requests\Api\V1\GenerateLotteryCodeGeneratedRequest;
use App\Models\Api\V1\Lottery;
use App\Services\Api\V1\Conditions\Code\ConsecutiveNumbersCombinationsCondition;
use App\Services\Api\V1\Conditions\Code\ConsecutiveNumbersCombinationsInGeneratedSetsCondition;
use App\Services\Api\V1\Conditions\Code\ConsecutiveTwoNumbersCombinationsCondition;
use App\Services\Api\V1\Conditions\Code\SpecialCodeCondition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateLotteryCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $request;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GenerateLotteryCodeGeneratedRequest $request)
    {
        $this->request = $request->all();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = $this->request;

        $firstCondition = new ConsecutiveTwoNumbersCombinationsCondition;
        $firstCondition
            ->next(new ConsecutiveNumbersCombinationsCondition)
            ->next(new SpecialCodeCondition)
            ->next(new ConsecutiveNumbersCombinationsInGeneratedSetsCondition);

        $lottery = Lottery::findOrFail(1);
        $j = 0;

        for ($i = 0; $i < $request['count']; $i++) {
            do {
                $j++;
                $code = \LotteryCodeService::generateLotteryCode($lottery, true);
                $conditionResult = $firstCondition->handle($code);
            } while ($conditionResult === false);
            \LotteryCodeService::storeLotteryCode($lottery, $code, true);
        }
    }
}
