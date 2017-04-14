<?php

namespace App\Modules\Rbac\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends Request
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
        $userId = $this->input('id', 0);
        $nameRule = $userId ? 'required|min:2|max:24|unique:users,name,' . $userId : 'required|min:2|max:24|unique:users,name';
        $emailRule = $userId ? 'required|email|unique:users,email,' . $userId : 'required|email|unique:users,email';
        $passwordRule = $userId ? 'sometimes|min:8|max:18' : 'required|min:8|max:18';
        return [
            'name' => $nameRule,
            'email' => $emailRule,
            'password' => $passwordRule,
            'repassword' => 'same:password',
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
            'name.required' => '请填写姓名',
            'name.max' => '姓名过长，请不要超出24个字符',
            'name.min' => '姓名过短，至少2个字符',
            'name.unique' => '姓名已存在',
            'email.required' => '请填写邮箱',
            'email.unique' => '邮箱已存在',
            'password.required' => '请填写密码',
            'password.min' => '密码过短，至少8个字符',
            'password.max' => '密码过长，请不要超出18个字符',
            'repassword.same' => '2次密码不一致',
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
