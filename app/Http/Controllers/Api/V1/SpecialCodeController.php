<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCodeRequest;
use App\Http\Requests\Api\V1\UpdateCodeRequest;
use App\Http\Resources\Api\V1\SpecialCodeCollection;
use App\Http\Resources\Api\V1\SpecialCodeResource;
use App\Models\Api\V1\Code;
use App\Models\Api\V1\SpecialCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SpecialCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $specialCodes = Cache::tags(['codes', 'special'])->remember('page_' . $request->get('page', 1), Carbon::now()->addDay(), function () {
            return SpecialCode::has('code')->orderBy('code_id', 'desc')->paginate(15);
        });

        return new SpecialCodeCollection($specialCodes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreCodeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCodeRequest $request)
    {
        $specialCode = Code::create([
            'code' => $request->code,
        ])->special()->create();

        return new SpecialCodeResource($specialCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function show(Code $code)
    {
        $specialCode = SpecialCode::whereRelation('code', 'id', $code->id)->firstOrFail();

        return new SpecialCodeResource($specialCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateCodeRequest  $request
     * @param  \App\Models\Api\V1\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCodeRequest $request, Code $code)
    {
        $specialCode = SpecialCode::whereRelation('code', 'id', $code->id)->firstOrFail();

        $specialCode->code()->update([
            'code' => $request->code,
        ]);

        return $this->show($specialCode->code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\V1\SpecialCode  $specialCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecialCode $specialCode)
    {
        //
    }
}
