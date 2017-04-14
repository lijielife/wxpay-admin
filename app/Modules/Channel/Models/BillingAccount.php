<?php

namespace App\Modules\Channel\Models;

use Illuminate\Database\Eloquent\Model;

class BillingAccount extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'billing_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_id', 'card_no', 'account_holder', 'account_type', 'cert_type', 'cert_no', 'mobile', 'branch_name', 'province', 'city',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * 获取所有对应的渠道
     */
    public function channels()
    {
        return $this->morphedByMany('App\Modules\Channel\Models\Channel', 'mapping', 'billing_account_mapping', 'mapping_id', 'billing_account_id');
    }

    /**
     * 获取所有对应渠道
     */
    public function merchants()
    {
        return $this->morphedByMany('App\Modules\Merchant\Models\Merchant', 'mapping', 'billing_account_mapping', 'mapping_id', 'billing_account_id');
    }

    /**
     * 获取所有对应渠道支付方式配置
     */
    public function merchantPayments()
    {
        return $this->morphedByMany('App\Modules\Merchant\Models\MerchantPayments', 'mapping', 'billing_account_mapping', 'mapping_id', 'billing_account_id');
    }

    /**
     * 所属银行
     */
    public function bank()
    {
        return $this->belongsTo('App\Modules\Rbac\Models\Bank', 'bank_id');
    }

}
