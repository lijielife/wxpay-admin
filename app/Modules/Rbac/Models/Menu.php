<?php

namespace App\Modules\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'state', 'url', 'icon',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * 菜单权限
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Modules\Rbac\Models\Permission', 'menu_permission');
    }

    /**
     * 子目录
     */
    public function children()
    {
        return $this->hasMany('App\Modules\Rbac\Models\Menu', 'pid');
    }

}
