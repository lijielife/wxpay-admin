<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('商户名称');
            $table->string('slug')->comment('商户简称');
            $table->integer('pid')->unsigned()->nullable()->comment('父商户ID');
            $table->integer('channel_id')->unsigned()->comment('所属渠道ID');
            $table->integer('department_id')->unsigned()->comment('所属部门，只有门店才有所属部门');
            $table->integer('industry_id')->unsigned()->nullable()->comment('所属行业ID');
            $table->enum('type', ['heavy', 'normal', 'direct', 'chain'])->default('normal')->comment('商户类型：大商户，普通商户，直营店，加盟店');
            $table->string('serial_no')->comment('商户编号');
            $table->string('province')->nullable()->comment('所在省');
            $table->string('city')->nullable()->comment('所在市');
            $table->string('address')->nullable()->comment('地址');
            $table->string('manager')->comment('负责人');
            $table->string('manager_mobile')->comment('负责人手机');
            $table->string('identity_card')->comment('负责人身份证');
            $table->string('tel')->nullable()->comment('电话');
            $table->string('email')->comment('邮箱');
            $table->string('fax')->nullable()->comment('传真');
            $table->string('service_tel')->comment('服务电话');
            $table->string('website')->nullable()->comment('网址');
            $table->enum('product_type', ['all', 'entity', 'virtual'])->default('all')->comment('经营类型：全部，所有，虚拟');
            $table->boolean('interface_refund_audit')->default(0)->comment('接口退款是否需要商户审核');
            $table->string('business_licence_pic')->comment('营业执照图片');
            $table->json('identity_card_pic')->comment('身份证正反面图片');
            $table->string('org_code_cert_pic')->comment('组织机构代码证图片');
            $table->json('merchant_protocol_pic')->comment('商户协议图片');
            $table->json('secret_key')->nullable()->comment('商户密钥，用来加密交易请求');
            $table->integer('examine_by')->nullable()->comment('审核人ID');
            $table->timestamp('examine_at')->nullable()->comment('审核日期');
            $table->enum('examine_status', ['not', 'success', 'failed', 'again'])->default('not')->comment('审核状态'); // 未审核、审核通过、审核失败、需再次审核
            $table->string('examine_remark')->nullable()->comment('审核备注');
            $table->integer('activate_by')->nullable()->comment('激活人ID');
            $table->timestamp('activate_at')->nullable()->comment('激活日期');
            $table->enum('activate_status', ['not', 'success', 'failed', 'again', 'freeze'])->default('not')->comment('激活状态'); // 未激活、激活成功、激活失败、需再次激活、冻结
            $table->string('activate_remark')->nullable()->comment('激活备注');
            $table->integer('created_by')->comment('创建人ID');
            $table->timestamps();
            $table->foreign('pid')->references('id')->on('merchants');
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->foreign('industry_id')->references('id')->on('industry');
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
        Schema::drop('merchants');
    }
}
