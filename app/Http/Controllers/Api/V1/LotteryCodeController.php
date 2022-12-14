<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreLotteryCodeRequest;
use App\Http\Requests\Api\V1\UpdateLotteryCodeRequest;
use App\Http\Resources\Api\V1\LotteryCodeCollection;
use App\Http\Resources\Api\V1\LotteryCodeResource;
use App\Models\Api\V1\Code;
use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LotteryCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lotteryCodes = Cache::tags(['lotteries', 'codes'])->remember('page_' . $request->get('page', 1), Carbon::now()->addDay(), function () {
            return LotteryCode::with(['lottery', 'code'])
                ->orderBy('code_id', 'desc')
                ->paginate(15);
        });

        return new LotteryCodeCollection($lotteryCodes);
    }

    /**
     * Display a listing of the resource by relation model.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByLottery(Request $request, Lottery $lottery)
    {
        $lotteryCodes = Cache::tags(['lotteries', 'codes', 'lottery_' . $lottery->id])->remember('page_' . $request->get('page', 1), Carbon::now()->addDay(), function () use ($lottery) {
            return LotteryCode::with(['lottery', 'code'])
                ->whereRelation('lottery', 'id', $lottery->id)
                ->orderBy('code_id', 'desc')
                ->paginate(15);
        });

        return new LotteryCodeCollection($lotteryCodes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLotteryCodeRequest $request, Lottery $lottery)
    {
        $code = Code::create([
            'code' => $request->code,
        ]);

        $lotteryCode = LotteryCode::create([
            'lottery_id' => $lottery->id,
            'code_id' => $code->id,
        ]);

        return new LotteryCodeResource($lotteryCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function show(Code $code)
    {
        $lotteryCode = LotteryCode::with(['lottery', 'code'])
            ->whereRelation('code', 'id', $code->id)
            ->firstOrFail();

        return new LotteryCodeResource($lotteryCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Api\V1\Code  $lotteryCode
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLotteryCodeRequest $request, Code $code)
    {
        $code->update([
            'code' => $request->code,
        ]);

        $code->lotteries()->sync($request->lottery);

        return $this->show($code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\V1\Code  $lotteryCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Code $code)
    {
        $lotteryCode = Code::has('lotteries')->findOrFail($code->id);

        return $lotteryCode->delete();
    }
}
