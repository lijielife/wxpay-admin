<?php

namespace App\Modules\Channel\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channels';

    /**
     * 供morphOne使用，对应billing_accounts表中table值
     *
     * @var string
     */
    protected $morphClass = 'channels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'province', 'city', 'address', 'manager', 'tel', 'email', 'remark',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function scopeRoleLimit($query)
    {
        $user = Auth::user();
        // 如果当前用户为渠道管理员角色，则只能看到自己渠道下的商户
        if ($user->mapping_type == 'channels') {
            return $query->where('id', $user->mapping_id);
        } elseif ($user->mapping_type == 'merchants') {
            $channel = $user->mapping()->getResults()->channel()->first();
            return $query->where('id', $channel->id);
        } elseif ($user->mapping_type == 'cashiers') {
            $channel = $user->mapping()->getResults()->merchant()->first()->channel()->first();
            return $query->where('id', $channel->id);
        }
        return $query;
    }

    public function getExamineStatusAttribute($value)
    {
        $map = [
            'not' => '未审核',
            'success' => '审核通过',
            'failed' => '审核不通过',
            'again' => '需再次审核',
        ];

        return isset($map[$value]) ? $map[$value] : $map['not'];
    }

    public function getActivateStatusAttribute($value)
    {
        $map = [
            'not' => '未激活',
            'success' => '已激活',
            'failed' => '激活失败',
            'again' => '需再次激活',
        ];

        return isset($map[$value]) ? $map[$value] : $map['not'];
    }

    public function getTypeAttribute($value)
    {
        $map = [
            'company' => '公司',
            'salesman' => '业务员',
        ];

        return isset($map[$value]) ? $map[$value] : $map['company'];
    }

    /**
     * 父级渠道
     */
    public function parent()
    {
        return $this->belongsTo('App\Modules\Channel\Models\Channel', 'pid');
    }

    /**
     * 渠道支付方式
     */
    public function payments()
    {
        return $this->hasMany('App\Modules\Channel\Models\ChannelPayment', 'channel_id');
    }

    /**
     * 渠道下商户
     */
    public function merchants()
    {
        return $this->hasMany('App\Modules\Merchants\Models\Merchant', 'channel_id');
    }

    /**
     * 渠道结款账号
     */
    public function account()
    {
        return $this->morphToMany('App\Modules\Channel\Models\BillingAccount', 'mapping', 'billing_account_mapping', 'mapping_id', 'billing_account_id');
    }

    /**
     * 渠道管理账号
     */
    public function adminAccount()
    {
        return $this->morphOne('App\Modules\Rbac\Models\User', 'mapping', 'mapping_type', 'mapping_id', 'id');
    }

    /**
     * 省
     */
    public function provinceInfo()
    {
        return $this->belongsTo('App\Modules\Rbac\Models\Region', 'province');
    }

    /**
     * 城市
     */
    public function cityInfo()
    {
        return $this->belongsTo('App\Modules\Rbac\Models\Region', 'city');
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
