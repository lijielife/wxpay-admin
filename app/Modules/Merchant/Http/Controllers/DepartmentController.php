<?php

namespace App\Modules\Merchant\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Merchant\Http\Requests\DepartmentRequest;
use App\Modules\Merchant\Models\Department;
use App\Modules\Merchant\Services\DepartmentService;
use Request;

class DepartmentController extends Controller
{

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index()
    {
        return view('merchant::department.list');
    }

    public function datagrid()
    {
        $result = $this->departmentService->getList();
        return response()->json($result);
    }

    public function add()
    {
        return view('merchant::department.add');
    }

    public function edit()
    {
        $id = Request::input('id', 0);
        $data['department'] = Department::with('parent', 'merchant')->find($id);

        return view('merchant::department.edit', $data);
    }

    public function save(DepartmentRequest $departmentRequest)
    {
        $result = $this->departmentService->save($departmentRequest);
        return response()->json(['success' => $result, 'msg' => $result ? '保存成功！' : '保存失败！']);
    }

    public function destroy()
    {
        $result = $this->departmentService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result === true ? '删除成功' : $result];
        return response()->json($data);
    }

}
