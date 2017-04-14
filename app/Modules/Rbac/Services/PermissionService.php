<?php

namespace App\Modules\Rbac\Services;

use App\Modules\Rbac\Models\Menu;
use App\Modules\Rbac\Models\Permission;
use Request;

class PermissionService
{
    public function getList()
    {
        Request::flash();
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');

        $name = Request::input('name');
        $slug = Request::input('slug');

        $page = (int) Request::input('pageNumber', 1);
        $length = (int) Request::input('pageSize', 10);
        $start = ($page - 1) * $length;

        $permissions = Permission::with('menus')->where('name', 'like', "%{$name}%")->where('slug', 'like', "%{$slug}%")->orderBy($orderBy, $sort)->paginate($length, ['*'], 'pageNumber');
        $permissions = $permissions->toArray();
        $permissions['rows'] = $permissions['data'];
        $permissions['pages'] = $permissions['last_page'];
        unset($permissions['per_page'], $permissions['current_page'], $permissions['last_page'], $permissions['next_page_url'], $permissions['prev_page_url'], $permissions['from'], $permissions['to'], $permissions['data']);

        return $permissions;
    }

    public function find($id)
    {
        return Permission::find((int) $id);
    }

    public function destroy($id = 0)
    {
        $ids = $id ? $id : Request::input('ids', 0);
        // 数据库建立了级联删除，所以角色权限对应表和用户权限对应表相关数据会同时删除
        return Permission::destroy($ids);
    }

    public function save($request)
    {
        $permissionId = $request->input('id', 0);
        $permission = Permission::firstOrNew(['id' => $permissionId]);
        $permission->name = $request->name;
        $permission->slug = $request->slug;
        $permission->description = $request->description;
        $permission->model = $request->model;
        $permission->save();

        $menuId = $request->input('mid', 0);
        if ($menuId) {
            $menu = Menu::find($menuId);
            $permission->menus()->detach();
            $permission->menus()->save($menu);
        }

        return $permission;
    }
}
