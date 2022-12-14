<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class LotteryCodeSpecialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->lotteryCode->code->id,
            'code' => $this->lotteryCode->code->code,
            'lottery' => new LotteryResource($this->lotteryCode->lottery),
        ];
    }
}
