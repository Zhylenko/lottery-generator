<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreLotteryRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'bail',
                'required',
                'string',
                'unique:lotteries',
                'max:255',
            ],
            'numbers_count' => [
                'bail',
                'required',
                'numeric',
                'min:1',
                'max:255',
            ],
            'numbers_from' => [
                'bail',
                'required',
                'numeric',
                'lte:numbers_to',
            ],
            'numbers_to' => [
                'bail',
                'required',
                'numeric',
                'gte:numbers_from',
            ],
        ];
    }
}
