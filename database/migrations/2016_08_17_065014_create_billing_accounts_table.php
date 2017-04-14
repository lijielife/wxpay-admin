<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBillingAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bank_id')->unsigned()->comment('银行ID');
            $table->string('card_no')->comment('银行卡号');
            $table->string('account_holder')->comment('持卡人');
            $table->enum('account_type', ['company', 'person'])->comment('账户类型：公司，个人');
            $table->enum('cert_type', ['idcard', 'passport'])->comment('证件类型：身份证，护照');
            $table->string('cert_no')->nullable()->comment('证件号码');
            $table->string('mobile')->nullable()->comment('手机');
            $table->string('branch_name')->nullable()->comment('开户支行名称');
            $table->integer('province')->nullable()->comment('支行所在省');
            $table->integer('city')->nullable()->comment('支行所在市');
            $table->string('line_no')->nullable()->comment('网点号(联行号)');
            $table->timestamps();
            $table->foreign('bank_id')->references('id')->on('banks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('billing_accounts');
    }
}
