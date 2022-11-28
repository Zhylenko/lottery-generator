<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreLotteryRequest;
use App\Http\Requests\Api\V1\UpdateLotteryRequest;
use App\Http\Resources\Api\V1\LotteryCollection;
use App\Http\Resources\Api\V1\LotteryResource;
use App\Models\Api\V1\Lottery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LotteryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lotteries = Cache::tags(['lotteries'])->remember('page_' . $request->get('page', 1), Carbon::now()->addDay(), function () {
            return Lottery::orderBy('id', 'desc')->paginate(15);
        });

        return new LotteryCollection($lotteries);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLotteryRequest $request)
    {
        $lottery = Lottery::create([
            'name' => $request->name,
            'numbers_count' => $request->numbers_count,
            'numbers_from' => $request->numbers_from,
            'numbers_to' => $request->numbers_to,
        ]);

        return $this->show($lottery);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\V1\Lottery  $lottery
     * @return \Illuminate\Http\Response
     */
    public function show(Lottery $lottery)
    {
        return new LotteryResource($lottery);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Api\V1\Lottery  $lottery
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLotteryRequest $request, Lottery $lottery)
    {
        $lottery->update([
            'name' => $request->name,
            'numbers_count' => $request->numbers_count,
            'numbers_from' => $request->numbers_from,
            'numbers_to' => $request->numbers_to,
        ]);

        return $this->show($lottery);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\V1\Lottery  $lottery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lottery $lottery)
    {
        return $lottery->delete();
    }
}
