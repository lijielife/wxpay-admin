<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique()->comment('用户名');
            $table->string('email')->unique()->comment('邮箱');
            $table->string('mobile')->nullable()->unique()->comment('手机');
            $table->string('password')->comment('密码');
            $table->enum('mapping_type', ['channels', 'merchants', 'cashiers'])->comment('关联表名：渠道，商户，营业员');
            $table->integer('mapping_id')->unsigned()->comment('关联表ID');
            $table->rememberToken();
            $table->string('wx_openid')->comment('微信用户openid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
