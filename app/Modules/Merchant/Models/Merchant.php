<?php

namespace App\Modules\Merchant\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchants';

    /**
     * 供morphOne使用，对应billing_accounts表中table值
     *
     * @var string
     */
    protected $morphClass = 'merchants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'type', 'channel_id', 'pid', 'serial_no', 'merchant_no', 'province', 'city', 'address', 'manager', 'identity_card', 'tel', 'manager', 'manager_mobile', 'email', 'fax', 'service_tel', 'website', 'product_type', 'business_licence_pic', 'identity_card_pic', 'org_code_cert_pic', 'merchant_protocol_pic', 'interface_refund_audit', 'industry_id', 'department_id',
    ];

    protected $casts = [
        'identity_card_pic' => 'array',
        'merchant_protocol_pic' => 'array',
    ];

    public function scopeTypeIn($query, $type)
    {
        return !empty($type) ? $query->whereIn('type', $type) : $query;
    }

    public function scopeActivateStatus($query, $status)
    {
        return !empty($status) ? $query->where('activate_status', $status) : $query;
    }

    public function scopeRoleLimit($query)
    {
        $user = Auth::user();
        // 如果当前用户为渠道管理员角色，则只能看到自己渠道下的商户
        if ($user->mapping_type == 'channels') {
            return $query->where('channel_id', $user->mapping_id);
        } elseif ($user->mapping_type == 'merchants') {
            return $query->where('id', $user->mapping_id);
        } elseif ($user->mapping_type == 'cashiers') {
            $merchant = $user->mapping()->getResults()->merchant()->first();
            return $query->where('id', $merchant->id);
        }
        return $query;
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

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
            'heavy' => '大商户',
            'normal' => '普通商户',
            'direct' => '直营店',
            'chain' => '加盟店',
        ];

        return isset($map[$value]) ? $map[$value] : $map['normal'];
    }

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
     * 父级商户
     */
    public function parent()
    {
        return $this->belongsTo('App\Modules\Merchant\Models\Merchant', 'pid');
    }

    /**
     * 所属渠道
     */
    public function channel()
    {
        return $this->belongsTo('App\Modules\Channel\Models\Channel', 'channel_id');
    }

    /**
     * 所属行业
     */
    public function industry()
    {
        return $this->belongsTo('App\Modules\Rbac\Models\Industry', 'industry_id');
    }

    /**
     * 商户支付方式
     */
    public function payments()
    {
        return $this->hasMany('App\Modules\Merchant\Models\MerchantPayment', 'merchant_id');
    }

    /**
     * 商户对应的部门
     */
    public function department()
    {
        return $this->belongsTo('App\Modules\Merchant\Models\Department', 'department_id');
    }

    /**
     * 商户下的部门
     */
    public function departments()
    {
        return $this->hasMany('App\Modules\Merchant\Models\Department', 'merchant_id');
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
     * 商户收款账号
     */
    public function account()
    {
        return $this->morphToMany('App\Modules\Channel\Models\BillingAccount', 'mapping', 'billing_account_mapping', 'mapping_id', 'billing_account_id');
    }

    /**
     * 商户管理账号
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
