<?php

namespace App\Modules\Rbac\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Rbac\Http\Requests\MenuRequest;
use App\Modules\Rbac\Models\Menu;
use App\Modules\Rbac\Services\MenuService;
use Request;

class MenuController extends Controller
{

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index()
    {
        return view('rbac::menu.list');
    }

    public function add()
    {
        $menus = Menu::all()->toArray();
        $data['menus'] = $this->menuService->selectTagTreeWrapper($menus);
        return view('rbac::menu.add', $data);
    }

    public function edit()
    {
        $id = Request::input('id', 0);
        $menus = Menu::all()->toArray();
        $data['menus'] = $this->menuService->selectTagTreeWrapper($menus);
        $data['menu'] = Menu::find($id);
        return view('rbac::menu.edit', $data);
    }

    public function save(MenuRequest $menuRequest)
    {
        $result = $this->menuService->save($menuRequest);
        return response()->json(['success' => (boolean) $result, 'msg' => $result ? '保存成功！' : '保存失败！']);
    }

    public function destroy()
    {
        $result = $this->menuService->destroy();
        $data = ['success' => (boolean) $result, 'msg' => $result ? '删除成功' : '删除失败'];
        return response()->json($data);
    }

    public function getMenusList()
    {
        $result = $this->menuService->getList();
        return response()->json($result);
    }

    public function getMenuPermissionTree()
    {
        $result = $this->menuService->getMenuPermissionTree();
        return response()->json($result);
    }

    public function nav()
    {
        $result = $this->menuService->getMenus();
        return response()->json($result);
    }

}
