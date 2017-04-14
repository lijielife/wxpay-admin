<?php

namespace App\Modules\Channel\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class ChannelRequest extends Request
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
        $channelId = $this->input('id', 0);
        $examineStatus = $this->input('examine_status');
        $activateStatus = $this->input('activate_status');
        $inputAccountInfoFlag = $this->input('input_account_info_flag', 0);

        $rules = [
            'name' => $channelId ? 'required|unique:channels,name,' . $channelId : 'required|unique:channels,name',
            'province' => 'required',
            'city' => 'required',
            'address' => 'required',
            'manager' => 'required',
            'tel' => 'required',
            'email' => 'required',
            'type' => 'required|in:company,salesman',
        ];

        // 审核或激活时，不验证规则，其他字段就算提交，也不会保存
        if (($examineStatus || $activateStatus)) {
            $rules = [];
        }

        // 结算账户信息不能编辑
        if (!$channelId && $inputAccountInfoFlag) {
            $billingAccountRules = [
                'billing_account.bank_id' => 'required',
                'billing_account.card_no' => 'required|unique:billing_accounts,card_no',
                'billing_account.account_holder' => 'required',
                'billing_account.account_type' => 'required|in:company,person',
            ];

            $rules = array_merge($rules, $billingAccountRules);
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
            'name.required' => '请填写渠道名称',
            'province.required' => '请填写渠道所在省份',
            'city.required' => '请填写渠道所在城市',
            'address.required' => '请填写渠道地址',
            'manager.required' => '请填写负责人姓名',
            'tel.required' => '请填写负责人电话',
            'email.required' => '请填写负责人邮箱',
            'type.required' => '请填写渠道类型',
            'type.in' => '渠道类型必须是公司或业务员',
            // 绑定账户信息验证
            'billing_account.bank_id.required' => '请填写开户银行',
            'billing_account.card_no.required' => '请填写银行卡号',
            'billing_account.card_no.unique' => '银行卡号已存在',
            'billing_account.account_holder.required' => '请填写持卡人姓名',
            'billing_account.account_type.required' => '请填写账户类型',
            'billing_account.account_type.in' => '账户类型必须是公司或个人',
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
