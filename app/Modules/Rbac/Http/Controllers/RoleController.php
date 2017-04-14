<?php

namespace App\Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rbac\Http\Requests\RoleRequest;
use App\Modules\Rbac\Services\RoleService;
use DCN\RBAC\Models\Role;
use Request;

class RoleController extends Controller
{

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        return view('rbac::role.list');
    }

    public function add()
    {
        $roles = Role::all()->toArray();
        $data['roles'] = $this->roleService->selectTagTreeWrapper($roles);

        return view('rbac::role.add', $data);
    }

    public function edit()
    {
        $id = Request::input('id', 0);
        $roles = Role::all()->toArray();
        $data['roles'] = $this->roleService->selectTagTreeWrapper($roles);
        $data['role'] = Role::find($id);

        return view('rbac::role.edit', $data);
    }

    public function save(RoleRequest $roleRequest)
    {
        $result = $this->roleService->save($roleRequest);
        return response()->json(['success' => (boolean) $result, 'msg' => $result ? '保存成功！' : '保存失败！']);
    }

    public function destroy()
    {
        $result = $this->roleService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result ? '删除成功' : '删除失败'];
        return response()->json($data);
    }

    public function getRolesList()
    {
        $result = $this->roleService->getList();
        return response()->json($result);
    }

}
