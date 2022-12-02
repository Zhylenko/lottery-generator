<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Api\V1\Lottery;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLotteryCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'codeLottery' => $this->route('code')->lotteries()->first(),
        ]);

        if (Lottery::where('id', $this->lottery)->exists() && ($this->lottery != $this->route('code')->lotteries()->first()->id))
            $this->merge([
                'codeLottery' => Lottery::where('id', $this->lottery)->first(),
            ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'lottery' => [
                'bail',
                'required',
                'exists:lotteries,id',
            ],
            'code' => [
                'bail',
                'required',
                'array',
                'size:' . $this->codeLottery->numbers_count,
            ],
            'code.*' => [
                'bail',
                'integer',
                'between:' . $this->codeLottery->numbers_from . ',' . $this->codeLottery->numbers_to,
                // 'distinct',
            ],
        ];
    }
}
