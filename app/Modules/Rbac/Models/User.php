<?php

namespace App\Modules\Rbac\Models;

use DCN\RBAC\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;
use DCN\RBAC\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasRoleAndPermissionContract
{
    use HasRoleAndPermission;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'mobile', 'password', 'mapping_type', 'mapping_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeLikeEmail($query, $email)
    {
        return !empty($email) ? $query->where('email', 'like', "%{$email}%") : $query;
    }

    /**
     * 获取所有关联对象
     */
    public function mapping()
    {
        return $this->morphTo();
    }

    /**
     * 覆盖父类方法，修复找不到正确Model类的bug
     *
     * @param  string  $class
     * @return string
     */
    public function getActualClassNameForMorph($class)
    {
        switch ($class) {
            case 'cashiers':
                $class = '\App\Modules\Merchant\Models\Cashier';
                break;
            case 'merchants':
                $class = '\App\Modules\Merchant\Models\Merchant';
                break;
            case 'channels':
                $class = '\App\Modules\Channel\Models\Channel';
                break;
            default:
                $class = parent::getActualClassNameForMorph($class);
                break;
        }

        return $class;
    }
}
