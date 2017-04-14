<?php

namespace App\Modules\Transaction\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class QrBatchRequest extends Request
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
        $rules = [
            'channel_id' => 'required|integer',
            'num' => 'required|integer|in:10,20,30,50',
        ];

        return $rules;
    }

    /**
     * 自定义验证信息
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'channel_id.required' => '请选择渠道',
            'channel_id.integer' => '非法请求',
            'num.required' => '请选择数值',
            'num.integer' => '非法请求',
            'num.min' => '非法请求',
        ];

        return $messages;
    }

    /**
     * 自定义错误数组
     *
     * @return array
     */
    public function formatErrors(Validator $validator)
    {
        $errors = array_map(function ($item) {
            return $item[0];
        }, $validator->getMessageBag()->toArray());
        $errors = ['success' => false, 'msg' => join('<br/>', $errors)];
        return $errors;
    }
}
