<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('渠道名称');
            $table->integer('pid')->unsigned()->nullable()->comment('渠道简称');
            $table->string('serial_no')->nullable()->comment('渠道编号');
            $table->enum('type', ['company', 'salesman'])->comment('渠道类型');
            $table->integer('province')->comment('所在省');
            $table->integer('city')->comment('所在市');
            $table->string('address')->comment('地址');
            $table->string('manager')->comment('负责人');
            $table->string('tel')->comment('电话');
            $table->string('email')->comment('邮箱');
            $table->string('remark')->nullable()->comment('备注');
            $table->string('invite_code')->nullable()->comment('邀请码');
            $table->integer('examine_by')->nullable()->comment('审核人ID');
            $table->timestamp('examine_at')->nullable()->comment('审核日期');
            $table->enum('examine_status', ['not', 'success', 'failed', 'again'])->default('not')->comment('审核状态'); // 未审核、审核通过、审核失败、需再次审核
            $table->string('examine_remark')->nullable()->comment('审核备注');
            $table->integer('activate_by')->nullable()->comment('激活人ID');
            $table->timestamp('activate_at')->nullable()->comment('激活日期');
            $table->enum('activate_status', ['not', 'success', 'failed', 'again', 'freeze'])->default('not')->comment('激活状态'); // 未激活、激活成功、激活失败、需再次激活、冻结
            $table->string('activate_remark')->nullable()->comment('激活备注');
            $table->timestamps();
            $table->foreign('pid')->references('id')->on('channels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('channels');
    }
}
