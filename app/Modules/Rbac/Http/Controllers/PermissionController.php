<?php

namespace App\Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rbac\Http\Requests\PermissionRequest;
use App\Modules\Rbac\Models\Menu;
use App\Modules\Rbac\Models\Permission;
use App\Modules\Rbac\Services\MenuService;
use App\Modules\Rbac\Services\PermissionService;
use Request;

class PermissionController extends Controller
{

    public function __construct(PermissionService $permissionService, MenuService $menuService)
    {
        $this->permissionService = $permissionService;
        $this->menuService = $menuService;
    }

    public function index()
    {
        return view('rbac::permission.list');
    }

    public function add()
    {
        $menus = Menu::all()->toArray();
        $data['menus'] = $this->menuService->selectTagTreeWrapper($menus);
        return view('rbac::permission.add', $data);
    }

    public function edit()
    {
        $id = Request::input('id', 0);
        $menus = Menu::all()->toArray();
        $data['menus'] = $this->menuService->selectTagTreeWrapper($menus);
        $data['permission'] = Permission::find($id);
        $data['mid'] = $data['permission']->menus()->count() ? $data['permission']->menus()->first()->id : 0;

        return view('rbac::permission.edit', $data);
    }

    public function save(PermissionRequest $permissionRequest)
    {
        $result = $this->permissionService->save($permissionRequest);
        return response()->json(['success' => (boolean) $result, 'msg' => $result ? '保存成功！' : '保存失败！']);
    }

    public function destroy()
    {
        $result = $this->permissionService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result ? '删除成功' : '删除失败'];
        return response()->json($data);
    }

    public function getPermissionsList()
    {
        $result = $this->permissionService->getList();
        return response()->json($result);
    }

}
