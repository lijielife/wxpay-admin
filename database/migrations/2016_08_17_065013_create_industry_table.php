<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIndustryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industry', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->unsigned()->nullable()->comment('父行业ID');
            $table->string('name')->comment('行业名称');
            $table->enum('product_type', ['all', 'entity', 'virtual'])->default('all')->comment('经营类型：全部，实体，虚拟');
            $table->string('remark')->nullable()->comment('备注');
            $table->timestamps();
            $table->foreign('pid')->references('id')->on('industry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('industry');
    }
}
