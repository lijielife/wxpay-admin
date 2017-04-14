<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChannelPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->unsigned()->comment('所属渠道ID');
            $table->integer('payment_id')->unsigned()->comment('支付方式ID');
            $table->enum('rate_type', ['fixed', 'cost'])->default('fixed')->comment('费率类型'); // 固定费率、成本费率
            $table->decimal('billing_rate', 8, 3)->unsigned()->default(0)->comment('结算费率'); // 结算费率
            $table->enum('product_type', ['all', 'entity', 'virtual'])->default('all')->comment('商户经营类型：所有，实体，虚拟');
            $table->string('activate_by')->nullable()->comment('激活人ID');
            $table->timestamp('activate_at')->nullable()->comment('激活日期');
            $table->enum('activate_status', ['not', 'success', 'failed', 'again', 'freeze'])->default('not')->comment('激活状态'); // 未激活、激活成功、激活失败、需再次激活、冻结
            $table->string('activate_remark')->nullable()->comment('激活备注');
            $table->timestamps();
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('channel_payments');
    }
}
