<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CodeCollection;
use App\Http\Resources\Api\V1\CodeResource;
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
        $specialCodes =  Cache::tags(['codes', 'special'])->remember('page_' . $request->get('page', 1), Carbon::now()->addDay(), function () {
            return Code::has('special')->orderBy('id', 'desc')->paginate(15);
        });

        return new CodeCollection($specialCodes);
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
     * @param  \App\Models\Api\V1\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function show(Code $code)
    {
        $specialCode = Code::has('special')->findOrFail($code->id);

        return new CodeResource($specialCode);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Api\V1\SpecialCode  $specialCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SpecialCode $specialCode)
    {
        //
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
