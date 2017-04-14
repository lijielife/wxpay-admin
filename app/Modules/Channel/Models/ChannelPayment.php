<?php

namespace App\Modules\Channel\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelPayment extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'channel_payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel_id', 'payment_id', 'rate_type', 'billing_rate', 'product_type',
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

    public function channel()
    {
        return $this->belongsTo('App\Modules\Channel\Models\Channel', 'channel_id');
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
