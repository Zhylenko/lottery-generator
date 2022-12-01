<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreLotteryCodeRequest extends FormRequest
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
            'code' => [
                'bail',
                'required',
                'array',
                'size:' . $this->lottery->numbers_count,
            ],
            'code.*' => [
                'bail',
                'integer',
                'between:' . $this->lottery->numbers_from . ',' . $this->lottery->numbers_to,
                // 'distinct',
            ],
        ];
    }
}
