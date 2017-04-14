<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMerchantPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('merchant_id')->unsigned()->comment('商户ID');
            $table->integer('payment_id')->unsigned()->comment('支付方式ID');
            $table->decimal('billing_rate', 8, 3)->unsigned()->default(0)->comment('结算费率');
            // $table->decimal('cost_rate', 2, 2)->unsigned(); // 成本费率
            $table->decimal('daily_trading_limit', 8, 2)->unsigned()->default(0)->comment('商户单日交易总额限额');
            $table->decimal('single_min_limit', 8, 2)->unsigned()->default(0)->comment('商户交易单笔最小限额');
            $table->decimal('single_max_limit', 8, 2)->unsigned()->default(0)->comment('商户交易单笔最大限额');
            $table->string('merchant_no')->comment('商户号');
            $table->string('activate_by')->nullable()->comment('激活人ID');
            $table->timestamp('activate_at')->nullable()->comment('激活日期');
            $table->enum('activate_status', ['not', 'success', 'failed', 'again', 'freeze'])->default('not')->comment('激活状态'); // 未激活、激活成功、激活失败、需再次激活、冻结
            $table->string('activate_remark')->nullable()->comment('激活备注');
            $table->timestamps();
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->foreign('billing_account_id')->references('id')->on('billing_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('merchant_payments');
    }
}
