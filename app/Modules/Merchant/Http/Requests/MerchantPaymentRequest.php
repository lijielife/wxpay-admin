<?php

namespace App\Modules\Merchant\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class MerchantPaymentRequest extends Request
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
        $merchantId = $this->input('id', 0);
        $activateStatus = $this->input('activate_status');
        $billingAccountId = $this->input('billing_account_id', 0);

        $rules = [
            'billing_rate' => 'required|numeric|min:0',
            'payment_id' => 'required|integer',
            'daily_trading_limit' => ['required', 'regex:/^-?\d+\.?\d{0,2}$/'],
            'single_min_limit' => ['required', 'regex:/^-?\d+\.?\d{0,2}$/'],
            'single_max_limit' => ['required', 'regex:/^-?\d+\.?\d{0,2}$/'],
            'merchant_no' => 'required',
            'billing_account_id' => 'required',
        ];

        // 审核或激活时，不验证规则，其他字段就算提交，也不会保存
        if ($activateStatus) {
            $rules = [];
        }

        // 结算账户可以编辑
        if (!$billingAccountId) {
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
            'payment_id.required' => '请填写支付类型',
            'billing_account_id.required' => '请选择结算账号',
            'daily_trading_limit.required' => '请填写商户日交易限额',
            'merchant_no.required' => '请填写商户号',
            'billing_rate.required' => '请填写结算费率',
            'billing_rate.regex' => '结算费率只能是数值，且小数点最多两位',
            'single_min_limit.required' => '请填写单笔交易最小限额',
            'single_min_limit.regex' => '单笔交易最小限额只能是数值，且小数点最多两位',
            'single_max_limit.required' => '请填写单笔交易最大限额',
            'single_max_limit.regex' => '单笔交易最大限额只能是数值，且小数点最多两位',

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
