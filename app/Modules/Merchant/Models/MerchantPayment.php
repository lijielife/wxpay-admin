<?php

namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantPayment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_payments';

    /**
     * 供morphOne使用，对应billing_accounts表中table值
     *
     * @var string
     */
    protected $morphClass = 'merchant_payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_id', 'payment_id', 'rate_type', 'billing_rate', 'product_type', 'daily_trading_limit', 'single_min_limit', 'single_max_limit', 'merchant_no',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function getActivateStatusAttribute($value)
    {
        $map = [
            'not' => '未激活',
            'success' => '已激活',
            'failed' => '激活失败',
            'again' => '需再次激活',
            'freeze' => '冻结',
        ];

        return isset($map[$value]) ? $map[$value] : $map['not'];
    }

    public function getRateTypeAttribute($value)
    {
        $map = [
            'fixed' => '固定费率',
            'cost' => '成本费率',
        ];

        return isset($map[$value]) ? $map[$value] : $map['not'];
    }

    public function getProductTypeAttribute($value)
    {
        $map = [
            'all' => '全部',
            'entity' => '实体',
            'virtual' => '虚拟',
        ];

        return isset($map[$value]) ? $map[$value] : $map['not'];
    }

    /**
     * 对应支付方式
     */
    public function payment()
    {
        return $this->belongsTo('App\Modules\Rbac\Models\Payment', 'payment_id');
    }

    /**
     * 对应商户
     */
    public function merchant()
    {
        return $this->belongsTo('App\Modules\Merchant\Models\Merchant', 'merchant_id');
    }

    /**
     * 支付方式收款账号
     */
    public function account()
    {
        return $this->morphToMany('App\Modules\Channel\Models\BillingAccount', 'mapping', 'billing_account_mapping', 'mapping_id', 'billing_account_id');
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
