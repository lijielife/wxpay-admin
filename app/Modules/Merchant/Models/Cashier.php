<?php

namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cashiers';

    /**
     * 供morphOne使用，对应billing_accounts表中table值
     *
     * @var string
     */
    protected $morphClass = 'cashiers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'sex', 'merchant_id', 'department_name', 'duty', 'mobile', 'email', 'identity_card', 'enabled', 'remark', 'serial_no',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function getSexAttribute($value)
    {
        $map = [
            'male' => '男',
            'female' => '女',
        ];
        return isset($map[$value]) ? $map[$value] : '';
    }

    public function getEnabledAttribute($value)
    {
        $map = [
            '1' => '启用',
            '0' => '不启用',
        ];
        return isset($map[$value]) ? $map[$value] : '';
    }

    public function scopeLikeName($query, $name)
    {
        return !empty($name) ? $query->where('name', 'like', "%{$name}%") : $query;
    }

    /**
     * 所属商户
     */
    public function merchant()
    {
        return $this->belongsTo('App\Modules\Merchant\Models\Merchant', 'merchant_id');
    }

    /**
     * 收银员管理账号
     */
    public function adminAccount()
    {
        return $this->morphOne('App\Modules\Rbac\Models\User', 'mapping', 'mapping_type', 'mapping_id', 'id');
    }

    /**
     *  返回原始的没处理过的数据
     */
    public function attr($key, $original = false)
    {
        $value = $this->getAttributeFromArray($key);

        return $value;
    }
}
