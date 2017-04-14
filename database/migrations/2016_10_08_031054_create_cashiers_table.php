<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCashiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashiers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('姓名');
            $table->enum('sex', ['male', 'female'])->comment('性别');
            $table->string('serial_no')->comment('编号');
            $table->integer('merchant_id')->unsigned()->comment('所属商户ID');
            $table->string('department_name')->comment('部门名称');
            $table->string('duty')->nullable()->comment('职务');
            $table->string('mobile')->comment('手机');
            $table->string('email')->comment('邮箱');
            $table->string('identity_card')->nullable()->comment('身份证');
            $table->boolean('enabled')->default(1)->comment('是否启用');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::drop('cashiers');
    }
}
