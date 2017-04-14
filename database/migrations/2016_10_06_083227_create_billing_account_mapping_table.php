<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBillingAccountMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_account_mapping', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('mapping_type', ['channels', 'merchants', 'merchant_payments'])->comment('关联表名');
            $table->integer('mapping_id')->unsigned()->comment('关联表ID');
            $table->integer('billing_account_id')->unsigned()->comment('对应结算账户ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('billing_account_mapping');
    }
}
