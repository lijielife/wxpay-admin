<?php

namespace App\Modules\Rbac\Models;

use DCN\RBAC\Models\Permission;

class Permission extends Permission
{
    /**
     * 权限菜单
     */
    public function menus()
    {
        return $this->belongsToMany('App\Modules\Rbac\Models\Menu', 'menu_permission');
    }
}
