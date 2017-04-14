<?php

namespace App\Modules\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'industry';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'product_type', 'remark', 'pid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function getProductTypeAttribute($value)
    {
        $map = [
            'all' => '全部',
            'entity' => '实体',
            'virtual' => '虚拟',
        ];

        return isset($map[$value]) ? $map[$value] : $map['all'];
    }

    /**
     * 父级行业
     */
    public function parent()
    {
        return $this->belongsTo('App\Modules\Rbac\Models\Industry', 'pid');
    }

    /**
     * 行业下商户
     */
    public function merchant()
    {
        return $this->hasMany('App\Modules\Merchant\Models\Merchant', 'industry_id');
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
