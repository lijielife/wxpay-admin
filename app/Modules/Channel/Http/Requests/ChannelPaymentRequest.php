<?php

namespace App\Modules\Channel\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class ChannelPaymentRequest extends Request
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
        $channelPaymentId = $this->input('id', 0);
        $activateStatus = $this->input('activate_status');

        $rules = [
            'channel_id' => 'required',
            'payment_id' => 'required',
            'rate_type' => 'required|in:fixed,cost',
            'billing_rate' => ['required', 'regex:/^-?\d+\.?\d{0,2}$/'],
            'product_type' => 'required|in:all,entity,virtual',
        ];

        if ($activateStatus) {
            $rules = [];
        }

        return $rules;
    }

    /**
     * 自定义验证信息
     *
     * @return array
     */
    public function messages()
    {
        return [
            'channel_id.required' => '请填写渠道名称',
            'payment_id.required' => '请填写支付类型',
            'rate_type.required' => '请填写费率类型',
            'rate_type.in' => '费率类型非法',
            'billing_rate.required' => '请填写结算费率',
            'billing_rate.regex' => '结算费率只能是数值，且小数点最多两位',
            'product_type.required' => '请填写商品经营类型',
            'product_type.required' => '商品经营类型非法',
        ];
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
