<?php

namespace App\Jobs\Api\V1;

use App\Contracts\Api\V1\Condition;
use App\Http\Requests\Api\V1\GenerateLotteryCodeGeneratedRequest;
use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode;
use App\Services\Api\V1\Conditions\Code\CodeCombinationsCondition;
use App\Services\Api\V1\Conditions\Code\ConsecutiveNumbersCombinationsCondition;
use App\Services\Api\V1\Conditions\Code\ConsecutiveNumbersCombinationsInGeneratedSetsCondition;
use App\Services\Api\V1\Conditions\Code\ConsecutiveTwoNumbersCombinationsCondition;
use App\Services\Api\V1\Conditions\Code\SpecialCodeCondition;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

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
        $lottery = Lottery::findOrFail($request['lottery']);
        $condition = null;

        if ($request['exclude_sets_of_consecutive_2_numbers_combinations'] == true) {
            $newCondition = new ConsecutiveTwoNumbersCombinationsCondition;
            $condition = $condition instanceof Condition ? $condition->next($newCondition) : $firstCondition = $newCondition;
        }

        if ($request['exclude_sets_of_consecutive_numbers_combinations'] == true) {
            $newCondition = new ConsecutiveNumbersCombinationsCondition;
            $condition = $condition instanceof Condition ? $condition->next($newCondition) : $firstCondition = $newCondition;
        }

        if ($request['exclude_generated_sets_of_consecutive_numbers_combinations'] == true) {
            $newCondition = new ConsecutiveNumbersCombinationsInGeneratedSetsCondition($lottery);
            $condition = $condition instanceof Condition ? $condition->next($newCondition) : $firstCondition = $newCondition;
        }

        if ($request['exclude_special_codes_combinations'] == true) {
            $newCondition = new SpecialCodeCondition($lottery);
            $condition = $condition instanceof Condition ? $condition->next($newCondition) : $firstCondition = $newCondition;
        }

        foreach ($request['exclude_code_combinations'] as $exclude_code_combination) {
            $newCondition = new CodeCombinationsCondition(
                $lottery,
                $exclude_code_combination['numbers'],
                $exclude_code_combination['count'],
                $exclude_code_combination['from'],
                $exclude_code_combination['to'],
            );

            $condition = $condition instanceof Condition ? $condition->next($newCondition) : $firstCondition = $newCondition;
        }

        Cache::tags(['lotteries', 'codes', 'generated', 'lottery_' . $lottery->id])->flush();

        for ($i = 0; $i < $request['count']; $i++) {
            $conditionResult = false;

            do {
                $code = \LotteryCodeService::generateLotteryCode($lottery, true);
                $codeString = json_encode($code);

                if (Cache::tags(['lotteries', 'codes', 'generated', 'lottery_' . $lottery->id])->has($codeString))
                    continue;

                $conditionResult = $firstCondition->handle($code);

                Cache::tags(['lotteries', 'codes', 'generated', 'lottery_' . $lottery->id])->forever($codeString, 1);
            } while ($conditionResult === false);

            if (LotteryCode::has('generated')->whereRelation('lottery', 'id', $lottery->id)->count() >= $request['count'])
                return;

            \LotteryCodeService::storeLotteryCode($lottery, $code, true);
        }
    }
}
