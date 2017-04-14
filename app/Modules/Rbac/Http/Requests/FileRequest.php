<?php

namespace App\Modules\Rbac\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class FileRequest extends Request
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
        $type = $this->input('type', 0);
        if ($type == 'image') {
            $fileRule = 'required|max:2048|mimes:jpg,jpeg,bmp,png,gif'; // 2M
        } else {
            $fileRule = 'required|max:2048'; // 2M
        }

        return [
            'file' => $fileRule,
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
            'file.required' => '请选择上传文件',
            'file.max' => '上传文件不能超过2M',
            'file.mimes' => '请选择图片文件，支持类型有:jpg,bmp,png,gif',
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
