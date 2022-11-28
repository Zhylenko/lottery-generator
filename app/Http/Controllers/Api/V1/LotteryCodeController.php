<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LotteryCodeCollection;
use App\Http\Resources\Api\V1\LotteryCodeResource;
use App\Models\Api\V1\Lottery;
use App\Models\Api\V1\LotteryCode;
use Illuminate\Http\Request;

class LotteryCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Lottery $lottery = null)
    {
        $lotteryCodes = new LotteryCode;

        if ($lottery)
            $lotteryCodes = $lotteryCodes::where('lottery_id', $lottery->id);

        return new LotteryCodeCollection($lotteryCodes->paginate(15));
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
     * @param  \App\Models\Api\V1\LotteryCode  $lotteryCode
     * @return \Illuminate\Http\Response
     */
    public function show(LotteryCode $lotteryCode)
    {
        return new LotteryCodeResource($lotteryCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Api\V1\LotteryCode  $lotteryCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LotteryCode $lotteryCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\V1\LotteryCode  $lotteryCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(LotteryCode $lotteryCode)
    {
        //
    }
}
