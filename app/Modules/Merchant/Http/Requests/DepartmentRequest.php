<?php

namespace App\Modules\Merchant\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class DepartmentRequest extends Request
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
        $bankId = $this->input('id', 0);
        $nameRule = $bankId ? 'required|min:4|max:50|unique:banks,name,' . $bankId : 'required|min:4|max:50|unique:banks,name';
        return [
            'name' => $nameRule,
            'merchant_id' => 'required',
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
            'name.required' => '请填写部门名称',
            'name.max' => '部门名称过长，请不要超出50个字符',
            'name.min' => '部门名称过短，至少4个字符',
            'name.unique' => '部门名称已存在',
            'merchant_id.required' => '请选择所属大商户',
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
