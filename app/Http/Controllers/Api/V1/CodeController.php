<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCodeRequest;
use App\Http\Resources\Api\V1\CodeCollection;
use App\Http\Resources\Api\V1\CodeResource;
use App\Models\Api\V1\Code;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $codes = Cache::tags(['codes'])->remember('page_' . $request->get('page', 1), Carbon::now()->addDay(), function () {
            return Code::orderBy('id', 'desc')->paginate(15);
        });

        return new CodeCollection($codes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCodeRequest $request)
    {
        $code = Code::create([
            'code' => $request->code,
        ]);

        return $this->show($code);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function show(Code $code)
    {
        return new CodeResource($code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Api\V1\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Code $code)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\V1\Code  $code
     * @return \Illuminate\Http\Response
     */
    public function destroy(Code $code)
    {
        //
    }
}
