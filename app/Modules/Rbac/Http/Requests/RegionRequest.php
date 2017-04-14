<?php

namespace App\Modules\Rbac\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

class RegionRequest extends Request
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
        $regionId = $this->input('id', 0);
        $nameRule = $regionId ? 'required|min:4|max:50|unique:region,name,' . $regionId : 'required|min:4|max:50|unique:region,name';
        $shortNameRule = $regionId ? 'required|min:4|max:50|unique:region,short_name,' . $regionId : 'required|min:4|max:50|unique:region,short_name';
        $zipCodeRule = $regionId ? 'required|size:6|unique:region,zip_code,' . $regionId : 'required|size:6|unique:region,zip_code';
        $cityCodeRule = $regionId ? 'required|min:3|max:4|unique:region,city_code,' . $regionId : 'required|min:3|max:4|unique:region,city_code';
        $areaCodeRule = $regionId ? 'sometimes|required|size:12|unique:region,area_code,' . $regionId : 'sometimes|required|size:12|unique:region,area_code';
        return [
            'name' => $nameRule,
            'short_name' => $shortNameRule,
            'zip_code' => $zipCodeRule,
            'city_code' => $cityCodeRule,
            'area_code' => $areaCodeRule,
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
            'name.required' => '请填写地区',
            'name.max' => '地区名称过长，请不要超出50个字符',
            'name.min' => '地区名称过短，至少4个字符',
            'name.unique' => '地区名称已存在',
            'short_name.required' => '请填写简称',
            'short_name.max' => '简称过长，请不要超出50个字符',
            'short_name.min' => '简称过短，至少4个字符',
            'short_name.unique' => '简称已存在',
            'zip_code.required' => '请填写邮政编码',
            'zip_code.size' => '邮政编码必须是6位',
            'zip_code.unique' => '邮政编码已存在',
            'city_code.required' => '请填写区号',
            'city_code.max' => '区号过长，请不要超出4个字符',
            'city_code.min' => '区号过短，至少3个字符',
            'city_code.unique' => '区号已存在',
            'area_code.required' => '请填写区域码',
            'area_code.size' => '区域码必须是12位',
            'area_code.unique' => '区域码已存在',
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
