<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->unsigned()->comment('渠道ID');
            $table->integer('merchant_id')->unsigned()->comment('商户ID');
            $table->integer('payment_id')->unsigned()->comment('支付类型ID');
            $table->integer('user_id')->unsigned()->nullable()->comment('操作员ID');
            $table->integer('qrcode_id')->unsigned()->nullable()->comment('二维码ID');
            $table->string('appid')->comment('公众账号ID');
            $table->string('mch_id')->comment('商户号');
            $table->string('device_info')->nullable()->comment('设备号');
            $table->string('openid')->nullable()->comment('用户标识');
            $table->string('is_subscribe')->nullable()->comment('是否关注公众账号');
            $table->string('trade_type')->comment('交易类型');
            $table->string('bank_type')->nullable()->comment('付款银行');
            $table->integer('total_fee')->comment('订单金额');
            $table->integer('settlement_total_fee')->nullable()->comment('应结订单金额');
            $table->string('fee_type')->nullable()->comment('货币种类');
            $table->integer('cash_fee')->nullable()->comment('现金支付金额');
            $table->string('cash_fee_type')->nullable()->comment('现金支付货币类型');
            $table->string('attach')->nullable()->comment('商家数据包');
            $table->string('transaction_id')->nullable()->comment('微信支付订单号');
            $table->string('out_trade_no')->comment('商户订单号');
            $table->string('spbill_create_ip')->nullable()->comment('APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP');
            $table->string('time_end')->nullable()->comment('支付完成时间');
            $table->string('return_code')->nullable()->comment('返回状态码');
            $table->string('result_code')->nullable()->comment('业务结果');
            $table->timestamps();

            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
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
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
