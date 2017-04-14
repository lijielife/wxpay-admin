<?php

namespace App\Modules\Merchant\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class CashierRequest extends Request
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
        $cashierId = $this->input('id', 0);
        $userId = $this->input('user_id', 0);
        $nameRule = $cashierId ? 'required|min:2|max:32|unique:cashiers,name,' . $cashierId : 'required|min:2|max:32|unique:cashiers,name';
        // 因为需要将手机号码和邮箱新建用户数据，所以需要判断在用户表里唯一
        $mobileRule = $cashierId ? 'required|unique:users,name,' . $userId : 'required|unique:users,name';
        $emailRule = $cashierId ? 'required|email|unique:users,email,' . $userId : 'required|email|unique:users,email';
        return [
            'name' => $nameRule,
            'merchant_id' => 'required',
            'sex' => 'required|in:male,female',
            'mobile' => $mobileRule,
            'email' => $emailRule,
        ];
    }

    /**
     * 自定义验证信息
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '请填写收银员姓名',
            'name.max' => '收银员姓名过长，请不要超出32个字符',
            'name.min' => '收银员姓名过短，至少2个字符',
            'name.unique' => '收银员姓名已存在',
            'merchant_id.required' => '请选择所属商户',
            'sex.required' => '请选择性别',
            'mobile.required' => '请填写手机号码',
            'mobile.unique' => '收银员手机号码已存在',
            'email.required' => '请填写收银员邮箱',
            'email.unique' => '收银员邮箱已存在',
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
