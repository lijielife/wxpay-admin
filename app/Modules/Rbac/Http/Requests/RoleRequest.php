<?php

namespace App\Modules\Rbac\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class RoleRequest extends Request
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
        $roleId = $this->input('id', 0);
        $nameRule = $roleId ? 'required|min:2|max:24|unique:roles,name,' . $roleId : 'required|min:2|max:24|unique:roles,name';
        $slugRule = $roleId ? 'required|min:2|max:24|unique:roles,slug,' . $roleId : 'required|min:2|max:24|unique:roles,slug';
        return [
            'name' => $nameRule,
            'slug' => $slugRule,
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
            'name.required' => '请填写权限名称',
            'name.max' => '权限名称过长，请不要超出24个字符',
            'name.min' => '权限名称过短，至少2个字符',
            'name.unique' => '权限名称已存在',
            'slug.required' => '请填写别名名称',
            'slug.max' => '别名名称过长，请不要超出24个字符',
            'slug.min' => '别名名称过短，至少4个字符',
            'slug.unique' => '别名名称已存在',
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
