<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrBatchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_batchs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->unsigned()->comment('渠道ID');
            $table->integer('user_id')->unsigned()->comment('操作员ID');
            $table->integer('num')->unsigned()->comment('生成数量');
            $table->string('merchant_logo')->nullable()->comment('商户LOGO');
            $table->string('remark')->nullable()->comment('备注');
            $table->timestamps();

            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
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
        Schema::table('qr_batchs', function (Blueprint $table) {
            //
        });
    }
}
