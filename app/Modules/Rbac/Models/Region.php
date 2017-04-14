<?php

namespace App\Modules\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'region';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'short_name', 'zip_code', 'city_code', 'area_code', 'pid', 'lat', 'lng', 'pinyin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * 上级区域
     */
    public function parent()
    {
        return $this->belongsTo('App\Modules\Rbac\Models\Region', 'pid');
    }

}
