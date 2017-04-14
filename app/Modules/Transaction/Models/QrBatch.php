<?php

namespace App\Modules\Transaction\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class QrBatch extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'qr_batchs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel_id', 'user_id', 'num', 'merchant_logo', 'remark',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function scopeRoleLimit($query, $channelId = null)
    {
        $user = Auth::user();
        // 如果当前用户为渠道管理员角色，则只能看到自己渠道下的商户
        if ($user->mapping_type == 'channels') {
            return $query->where('channel_id', $channelId ? $channelId : $user->mapping_id);
        }
        return $query;
    }

    /**
     * 所属渠道
     */
    public function channel()
    {
        return $this->belongsTo('App\Modules\Channel\Models\Channel', 'channel_id');
    }

    /**
     * 操作员
     */
    public function operator()
    {
        return $this->belongsTo('App\Modules\Rbac\Models\User', 'user_id');
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
