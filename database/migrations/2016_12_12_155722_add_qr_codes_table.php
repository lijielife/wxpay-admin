<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('batch_id')->unsigned()->comment('批次ID');
            $table->integer('channel_id')->unsigned()->nullable()->comment('渠道ID');
            $table->integer('merchant_id')->unsigned()->nullable()->comment('商户ID');
            $table->integer('cashier_id')->unsigned()->nullable()->comment('收银员ID');
            $table->integer('user_id')->unsigned()->nullable()->comment('用户ID');
            $table->string('serial_no')->unique()->comment('唯一收款标识ID');
            $table->string('merchant_no')->nullable()->comment('冗余商户号');
            $table->timestamp('binded_at')->nullable()->comment('操作员ID');
            $table->enum('status', ['not', 'success', 'unbind'])->default('not')->comment('绑定状态');
            $table->timestamps();

            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
            $table->foreign('cashier_id')->references('id')->on('cashiers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            //
        });
    }
}
