<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->unsigned()->comment('渠道ID');
            $table->integer('merchant_id')->unsigned()->comment('商户ID');
            $table->integer('order_id')->unsigned()->index()->comment('订单ID');
            $table->integer('user_id')->unsigned()->comment('操作员ID');
            $table->string('mch_id')->comment('商户号');
            $table->string('device_info')->nullable()->comment('设备号');
            $table->string('transaction_id')->comment('微信支付订单号');
            $table->string('out_trade_no')->comment('商户订单号');
            $table->string('refund_id')->comment('微信退款单号');
            $table->string('out_refund_no')->comment('商户退款单号');
            $table->string('refund_channel')->nullable()->comment('退款渠道');
            $table->string('refund_fee')->comment('申请退款金额');
            $table->string('settlement_refund_fee')->comment('退款金额');
            $table->string('total_fee')->comment('订单金额');
            $table->string('settlement_total_fee')->nullable()->comment('应结订单金额');
            $table->string('fee_type')->nullable()->comment('货币种类');
            $table->string('cash_fee')->nullable()->comment('现金支付金额');
            $table->string('cash_fee_type')->nullable()->comment('现金支付货币类型');
            $table->string('return_code')->comment('返回状态码');
            $table->string('result_code')->comment('业务结果');
            $table->string('err_code')->comment('错误代码');
            $table->string('err_code_des')->comment('错误代码描述');
            $table->timestamps();

            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refunds', function (Blueprint $table) {
            //
        });
    }
}
