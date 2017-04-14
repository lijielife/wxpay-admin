<?php

namespace App\Modules\Merchant\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class MerchantRequest extends Request
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
        $type = $this->input('type');
        $examineStatus = $this->input('examine_status');
        $activateStatus = $this->input('activate_status');
        $inputAccountInfoFlag = $this->input('input_account_info_flag', 0);

        $rules = [
            'name' => $merchantId ? 'required|min:4|max:50|unique:merchants,name,' . $merchantId : 'required|min:4|max:50|unique:merchants,name',
            'slug' => $merchantId ? 'required|unique:merchants,slug,' . $merchantId : 'required|unique:merchants,slug',
            'product_type' => 'required|in:all,entity,virtual',
            'industry_id' => 'required|integer',
            'province' => 'required|integer',
            'city' => 'required|integer',
            'email' => 'required|email',
            'manager_mobile' => 'required',
            'service_tel' => 'required',
            'interface_refund_audit' => 'required|boolean',
            'type' => 'required|in:heavy,direct,normal',
            'business_licence_pic' => 'required',
            'org_code_cert_pic' => 'required',
            'identity_card_pic.front' => 'required',
            'identity_card_pic.back' => 'required',
            'merchant_protocol_pic' => 'required|array',
            'channel_id' => 'required_if:type,heavy|integer',
        ];

        // 审核或激活时，不验证规则，其他字段就算提交，也不会保存
        if (($examineStatus || $activateStatus)) {
            $rules = [];
        }

        if ($this->segment(2) == 'store') {
            $rules = [
                'name' => $merchantId ? 'required|min:4|max:50|unique:merchants,name,' . $merchantId : 'required|min:4|max:50|unique:merchants,name',
                'pid' => 'required',
                'department_id' => 'required',
                'type' => 'required|in:direct,chain',
            ];
        }

        // 结算账户信息不能编辑
        if (!$merchantId && $inputAccountInfoFlag) {
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
        if ($this->segment(2) == 'store') {
            $messages = [
                'name.required' => '请填写商户名称',
                'name.max' => '门店名称过长，请不要超出50个字符',
                'name.min' => '门店名称过短，至少4个字符',
                'name.unique' => '门店名称已存在',
                'pid.required' => '请选择所属大商户',
                'department_id.required' => '请选择所属部门',
                'type.required' => '请选择门店类型',
                'type.in' => '商户类型必须是直营店或加盟店',
            ];
        } else {
            $messages = [
                'name.required' => '请填写商户名称',
                'name.max' => '商户名称过长，请不要超出50个字符',
                'name.min' => '商户名称过短，至少4个字符',
                'name.unique' => '商户名称已存在',
                'slug.required' => '请填写商户简称',
                'type.in' => '商户类型必须是大商户或普通商户',
                'industry_id.required' => '请填写行业类别',
                'industry_id.integer' => '非法行业类别数据',
                'province.required' => '请填写商户所在省份',
                'province.integer' => '非法省份数据',
                'city.required' => '请填写商户所在城市',
                'city.integer' => '非法城市数据',
                'email.required' => '请填写负责人邮箱',
                'email.email' => '邮箱格式不正确',
                'manager_mobile.required' => '请填写负责人手机',
                'service_tel.required' => '请填写客服电话',
                'interface_refund_audit.required' => '请填写接口退款是否需要商户审核',
                'product_type.in' => '经营类型必须是所有、实体或虚拟',
                'business_licence_pic.required' => '请上传营业执照',
                'org_code_cert_pic.required' => '请上传组织机构代码证',
                'identity_card_pic.front.required' => '请上传商户负责人身份证正面',
                'identity_card_pic.back.required' => '请上传商户负责人身份证反面',
                'merchant_protocol_pic.required' => '请上传商户协议',
                'merchant_protocol_pic.array' => '非法商户协议数据',

                // 绑定账户信息验证
                'billing_account.bank_id.required' => '请填写开户银行',
                'billing_account.card_no.required' => '请填写银行卡号',
                'billing_account.card_no.unique' => '银行卡号已存在',
                'billing_account.account_holder.required' => '请填写持卡人姓名',
                'billing_account.account_type.required' => '请填写账户类型',
                'billing_account.account_type.in' => '账户类型必须是公司或个人',
            ];
        }

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
