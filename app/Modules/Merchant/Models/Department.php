<?php

namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'departments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'pid', 'merchant_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function scopeLikeName($query, $name)
    {
        return !empty($name) ? $query->where('name', 'like', "%{$name}%") : $query;
    }

    /**
     * 上级部门
     */
    public function parent()
    {
        return $this->belongsTo('App\Modules\Merchant\Models\Department', 'pid');
    }

    /**
     * 子部门
     */
    public function children()
    {
        return $this->hasMany('App\Modules\Merchant\Models\Department', 'pid');
    }

    /**
     * 所属大商户
     */
    public function merchant()
    {
        return $this->belongsTo('App\Modules\Merchant\Models\Merchant', 'merchant_id');
    }

}
