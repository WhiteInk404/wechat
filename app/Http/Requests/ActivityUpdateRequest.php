<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityUpdateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|string',
            'description' => 'required|string',
            'begin_time'  => 'required|date',
            'end_time'    => 'required|date|after:begin_time',
            'pic_url'     => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'begin_time'  => '开始时间',
            'end_time'    => '结束时间',
            'pic_url'     => '海报地址',
        ];
    }
}
