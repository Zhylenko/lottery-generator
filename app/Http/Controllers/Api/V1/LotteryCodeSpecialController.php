<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreLotteryCodeRequest;
use App\Http\Requests\Api\V1\UpdateLotteryCodeRequest;
use App\Http\Resources\Api\V1\LotteryCodeSpecialCollection;
use App\Http\Resources\Api\V1\LotteryCodeSpecialResource;
use App\Models\Api\V1\Code;
use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode;
use App\Models\Api\V1\LotteryCodeSpecial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LotteryCodeSpecialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lotteryCodesSpecial = Cache::tags(['lotteries', 'codes', 'special'])->remember('page_' . $request->get('page', 1), Carbon::now()->addDay(), function () {
            return LotteryCodeSpecial::with([
                'lotteryCode.lottery',
                'lotteryCode.code',
            ])
                ->join('lottery_code', 'lottery_code_special.lottery_code_id', '=', 'lottery_code.id')
                ->orderBy('lottery_code.code_id', 'desc')
                ->paginate(15);
        });

        return new LotteryCodeSpecialCollection($lotteryCodesSpecial);
    }

    /**
     * Display a listing of the resource by relation model.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByLottery(Request $request, Lottery $lottery)
    {
        $lotteryCodesSpecial = Cache::tags(['lotteries', 'codes', 'special', 'lottery_' . $lottery->id])->remember('page_' . $request->get('page', 1), Carbon::now()->addDay(), function () use ($lottery) {
            return LotteryCodeSpecial::with([
                'lotteryCode.lottery',
                'lotteryCode.code',
            ])
                ->whereRelation('lotteryCode.lottery', 'id', $lottery->id)
                ->join('lottery_code', 'lottery_code_special.lottery_code_id', '=', 'lottery_code.id')
                ->orderBy('lottery_code.code_id', 'desc')
                ->paginate(15);
        });

        return new LotteryCodeSpecialCollection($lotteryCodesSpecial);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreLotteryCodeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLotteryCodeRequest $request, Lottery $lottery)
    {
        $code = $lottery->codes()
            ->create([
                'code' => $request->code,
            ]);

        $lotteryCodeSpecial = LotteryCode::whereRelation('lottery', 'id', $lottery->id)
            ->whereRelation('code', 'id', $code->id)
            ->first()
            ->special()
            ->create();

        return new LotteryCodeSpecialResource($lotteryCodeSpecial);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function show(Code $code)
    {
        $lotteryCodeSpecial = LotteryCodeSpecial::with([
            'lotteryCode.lottery',
            'lotteryCode.code',
        ])
            ->whereRelation('lotteryCode.code', 'id', $code->id)
            ->firstOrFail();

        return new LotteryCodeSpecialResource($lotteryCodeSpecial);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateLotteryCodeRequest  $request
     * @param  \App\Models\Api\V1\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLotteryCodeRequest $request, Code $code)
    {
        $lotteryCodeSpecial = LotteryCodeSpecial::with([
            'lotteryCode.lottery',
            'lotteryCode.code',
        ])
            ->whereRelation('lotteryCode.code', 'id', $code->id)
            ->firstOrFail();

        $lotteryCode = $lotteryCodeSpecial->lotteryCode;
        $lottery = Lottery::findOrFail($request->lottery);

        $lotteryCode->update([
            'lottery_id' => $lottery->id,
        ]);

        $code = $lotteryCodeSpecial->lotteryCode->code;

        $code->update([
            'code' => $request->code,
        ]);

        return $this->show($code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy(Code $code)
    {
        $lotteryCodeSpecial = LotteryCodeSpecial::with([
            'lotteryCode.lottery',
            'lotteryCode.code',
        ])
            ->whereRelation('lotteryCode.code', 'id', $code->id)
            ->firstOrFail();

        $code = $lotteryCodeSpecial->lotteryCode->code;

        return $code->delete();
    }
}
