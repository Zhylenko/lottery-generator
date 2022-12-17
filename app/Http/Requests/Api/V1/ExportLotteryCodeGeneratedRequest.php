<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ExportLotteryCodeGeneratedRequest extends FormRequest
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
            'extension' => [
                'bail',
                'required',
                'string',
                'in:xlsx,xlsm,xltx,xltm,xls,xlt,ods,ots,slk,xml,gnumeric,htm,html,csv,tsv,pdf',
            ],
        ];
    }
}
