<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class GenerateLotteryCodeGeneratedRequest extends FormRequest
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
            'lottery' => [
                'bail',
                'required',
                'integer',
                'exists:lotteries,id',
            ],
            'exclude_code_combinations' => [
                'bail',
                'required',
                'array',
            ],
            'exclude_code_combinations.*' => [
                'bail',
                'required',
                'array',
            ],
            'exclude_code_combinations.*.numbers' => [
                'bail',
                'required',
                'integer',
                'min:1',
            ],
            'exclude_code_combinations.*.count' => [
                'bail',
                'required',
                'integer',
                'min:0',
            ],
            'exclude_code_combinations.*.from' => [
                'bail',
                'required',
                'integer',
                'min:1',
                'lte:exclude_code_combinations.*.to',
            ],
            'exclude_code_combinations.*.to' => [
                'bail',
                'required',
                'integer',
                'min:1',
                'gte:exclude_code_combinations.*.from',
            ],
            'exclude_sets_of_consecutive_2_numbers_combinations' => [
                'bail',
                'required',
                'boolean',
            ],
            'exclude_special_codes_combinations' => [
                'bail',
                'required',
                'boolean',
            ],
            'exclude_sets_of_consecutive_numbers_combinations' => [
                'bail',
                'required',
                'boolean',
            ],
            'exclude_generated_sets_of_consecutive_numbers_combinations' => [
                'bail',
                'required',
                'boolean',
            ],
            'count' => [
                'bail',
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}
