<?php

namespace App\Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel_id', 'merchant_id', 'user_id', 'appid', 'mch_id', 'device_info', 'openid', 'is_subscribe', 'trade_type', 'bank_type', 'total_fee', 'settlement_total_fee', 'fee_type', 'cash_fee', 'cash_fee_type', 'attach', 'transaction_id', 'out_trade_no', 'time_end', 'return_code', 'result_code', 'err_code', 'err_code_des',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function getTradeTypeAttribute($value)
    {
        $map = [
            'MICROPAY' => '刷卡支付',
            'NATIVE' => '扫码支付',
            'JSAPI' => '公众号支付',
        ];

        return isset($map[$value]) ? $map[$value] : $map['not'];
    }

    /**
     * 所属渠道
     */
    public function channel()
    {
        return $this->belongsTo('App\Modules\Channel\Models\Channel', 'channel_id');
    }

    /**
     * 对应商户
     */
    public function merchant()
    {
        return $this->belongsTo('App\Modules\Merchant\Models\Merchant', 'merchant_id');
    }

    /**
     * 对应支付方式
     */
    public function payment()
    {
        return $this->belongsTo('App\Modules\Rbac\Models\Payment', 'payment_id');
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
