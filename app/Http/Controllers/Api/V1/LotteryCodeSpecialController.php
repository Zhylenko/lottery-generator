<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LotteryCodeSpecialCollection;
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
            return LotteryCode::has('special')
                ->with(['lottery', 'code'])
                ->orderBy('code_id', 'desc')
                ->paginate(15);
        });

        return new LotteryCodeSpecialCollection($lotteryCodesSpecial);
    }

    public function indexByLottery(Request $request, Lottery $lottery)
    {
        $lotteryCodesSpecial = Cache::tags(['lotteries', 'codes', 'special', 'lottery_' . $lottery->id])->remember('page_' . $request->get('page', 1), Carbon::now()->addDay(), function () use ($lottery) {
            return LotteryCode::has('special')
                ->whereRelation('lottery', 'id', $lottery->id)
                ->with(['lottery', 'code'])
                ->orderBy('code_id', 'desc')
                ->paginate(15);
        });

        return new LotteryCodeSpecialCollection($lotteryCodesSpecial);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\V1\LotteryCodeSpecial  $lotteryCodeSpecial
     * @return \Illuminate\Http\Response
     */
    public function show(LotteryCodeSpecial $lotteryCodeSpecial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Api\V1\LotteryCodeSpecial  $lotteryCodeSpecial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LotteryCodeSpecial $lotteryCodeSpecial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\V1\LotteryCodeSpecial  $lotteryCodeSpecial
     * @return \Illuminate\Http\Response
     */
    public function destroy(LotteryCodeSpecial $lotteryCodeSpecial)
    {
        //
    }
}
