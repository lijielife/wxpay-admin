<?php

namespace App\Modules\Rbac\Services;

use App\Modules\Rbac\Models\Menu;
use App\Modules\Rbac\Models\User;
use Auth;
use DCN\RBAC\Models\Role;
use Request;

class MenuService
{

    public function getList()
    {
        Request::flash();
        $orderBy = Request::input('orderBy', 'id');
        $sort = Request::input('sort', 'desc');
        $pid = Request::input('id');

        $page = (int) Request::input('pageNumber', 1);
        $length = (int) Request::input('pageSize', 10);
        $start = ($page - 1) * $length;

        $menus = $pid ? Menu::where('pid', $pid)->get()->toArray() : Menu::all()->toArray();
        $menus = $this->buildTreeData($menus);

        return $menus;
    }

    public function getMenuPermissionTree()
    {
        $roleId = Request::input('rid', 0);
        $userId = Request::input('uid', 0);
        $showother = Request::input('showother', true);
        $permissionIds = [];

        if ($roleId) {
            $role = Role::find($roleId);
            if ($role) {
                $permissions = $role->permissions;
                foreach ($permissions as $key => $val) {
                    $permissionIds[] = $val->id;
                }
            }
        }

        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                // $permissions = $user->getPermissions();
                $permissions = $user->userPermissions()->get();
                foreach ($permissions as $key => $val) {
                    $permissionIds[] = $val->id;
                }
            }
        }

        if ($showother) {
            $menus = Menu::with('permissions')->get()->toArray();
            $menus = $this->buildTreeData($menus, 0, $permissionIds);
        } else {
            $menus = Menu::with(['permissions' => function ($query) use ($permissionIds) {
                $query->whereIn('permission_id', $permissionIds);
            }])->get()->toArray();
            $menus = $this->hideOther($menus);
        }

        return $menus;
    }

    private function hideOther($menu)
    {
        $menu1 = array_dot($menu);
        $menu2 = [];
        foreach ($menu as $key => $val) {
            if (!empty($val['permissions'])) {
                $val['text'] = $val['name'];
                is_null($val['pid']) && $val['pid'] = 0;
                foreach ($val['permissions'] as $k => $v) {
                    $v['text'] = $v['name'];
                    $v['iconCls'] = 'icon-ok';
                    $v['attr']['is_permission'] = true;
                    $val['children'][] = $v;
                }
                $menu2[$val['pid']] = $val;
            }
        }

        while (true) {
            foreach ($menu as $key => $val) {
                if (isset($menu2[$val['id']])) {
                    is_null($val['pid']) && $val['pid'] = 0;
                    $val['text'] = $val['name'];
                    $val['children'][] = $menu2[$val['id']];
                    // TODO 前台还没有显示出来
                    if ($val['pid'] > 0) {
                        $menu2[$val['pid']] = $val;
                        unset($menu2[$val['id']]);
                    } else {
                        $menu2[$val['id']] = $val;
                    }
                }
            }
            $t = array_map(function ($item) {
                if ($item['pid'] != 0) {
                    return $item;
                }
            }, $menu2);

            $flag = true;
            foreach ($menu2 as $key => $val) {
                if ($val['pid'] > 0) {
                    $flag = false;
                    break;
                }
            }
            if ($flag) {
                break;
            }
        }
        // EasyUI只认从0开始索引的数组
        $menu2 = array_values($menu2);
        return $menu2;
    }

    public function buildTreeData($menu, $pid = 0, $permissionIds = [])
    {
        $tree = [];

        foreach ($menu as $key => $val) {
            if ($val['pid'] == $pid) {
                if (isset($val['permissions'])) {
                    $val['attr']['is_permission'] = false;
                    $val['text'] = $val['name'];
                }
                $children = $this->buildTreeData($menu, $val['id'], $permissionIds);
                $children && $val['children'] = $children;
                if (isset($val['permissions'])) {
                    foreach ($val['permissions'] as $k => $v) {
                        if (in_array($v['id'], $permissionIds)) {
                            $v['checked'] = true;
                        }
                        $v['text'] = $v['name'];
                        $v['iconCls'] = 'icon-ok';
                        $v['attr']['is_permission'] = true;
                        $val['children'][] = $v;
                    }
                }
                $tree[] = $val;
            }
        }

        return $tree;
    }

    public function find($id)
    {
        return Menu::find((int) $id);
    }

    public function destroy($id = 0)
    {
        $ids = $id ? $id : Request::input('ids', 0);
        // 数据库建立了级联删除，所以菜单权限对应表数据会同时删除
        return Menu::destroy($ids);
    }

    public function save($request)
    {
        $menuId = $request->input('id', 0);
        $menu = Menu::firstOrNew(['id' => $menuId]);
        $menu->name = $request->name;
        $menu->pid = $request->pid ? $request->pid : null;
        $menu->icon = $request->icon;
        $menu->url = $request->url;
        $menu->state = $request->state;
        if ($menu->url) {
            $menu->state = 'open';
        }
        $menu->save();

        return $menu;
    }

    public function selectTagTreeWrapper($list, $pid = 0, $level = 0, $html = '┆┄')
    {
        static $tree = [];

        foreach ($list as $val) {
            if ($val['pid'] == $pid) {
                $val['level'] = $level;
                $val['html'] = str_repeat($html, $level);
                $tree[] = $val;
                $this->selectTagTreeWrapper($list, $val['id'], $level + 1);
            }
        }
        return $tree;
    }

    public function indexKey($data)
    {
        $arr = array_keys($data);

        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $arr[$key] = $this->indexKey($val);
            }
        }

        return $arr;
    }

    public function getMenus()
    {
        $menus = Menu::all();
        $user = Auth::user();

        // 如果是超级管理员，返回所有
        if ($user->is('super.admin')) {
            $menus = $menus->toArray();

            foreach ($menus as $key => &$val) {
                $val['attributes']['url'] = $val['url'];
                $val['attributes']['icon'] = $val['icon'];
                $val['text'] = $val['name'];
            }

            return $menus;
        }

        $menus = $menus->filter(function ($menu, $key) use ($user, $menus) {
            $permissions = $menu->permissions()->get();
            $result = false;
            // 如果有子目录，则先不删除
            if ($permissions->count() == 0 && $this->hasChildren($menus, $menu->id)) {
                return true;
            }
            // 只要当前用户具有菜单下的其中一个权限，则显示菜单
            foreach ($permissions as $permission) {
                if ($user->can($permission->slug)) {
                    $result = true;
                    break;
                }
            }

            return $result;
        });

        $count = $menus->count();
        while (true) {
            $menus = $menus->filter(function ($menu, $key) use ($user, $menus) {
                $permissions = $menu->permissions()->get();
                if ($permissions->count() == 0 && !$this->hasChildren($menus, $menu->id)) {
                    return false;
                }
                return true;
            });
            if ($menus->count() == $count) {
                break;
            }
            $count = $menus->count();
        }

        $menus = $menus->toArray();

        foreach ($menus as $key => &$val) {
            $val['attributes']['url'] = $val['url'];
            $val['attributes']['icon'] = $val['icon'];
            $val['text'] = $val['name'];
        }

        return array_values($menus);
    }

    private function hasChildren($menus, $pid)
    {
        $result = false;

        foreach ($menus as $menu) {
            if ($menu->pid === $pid) {
                $result = true;
                break;
            }
        }

        return $result;
    }
}
