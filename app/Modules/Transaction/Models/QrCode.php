<?php

namespace App\Modules\Transaction\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'qr_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel_id', 'merchant_id', 'cashier_id', 'merchant_no', 'binded_at', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function getStatusAttribute($value)
    {
        $map = [
            'not' => '未绑定',
            'success' => '已绑定',
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
     * 所属商户
     */
    public function merchant()
    {
        return $this->belongsTo('App\Modules\Merchant\Models\Merchant', 'merchant_id');
    }

    /**
     * 所属收银员
     */
    public function cashier()
    {
        return $this->belongsTo('App\Modules\Merchant\Models\Cashier', 'cashier_id');
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
