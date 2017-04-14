<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('region', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->unsigned()->comment('父区域ID');
            $table->tinyInteger('level')->comment('级别');
            $table->string('zip_code')->comment('邮编');
            $table->string('city_code')->comment('城市编码');
            $table->string('area_code')->comment('区域编码');
            $table->string('name')->comment('区域名称');
            $table->string('short_name')->comment('简称');
            $table->string('merge_name')->comment('全称');
            $table->string('pinyin')->comment('拼音');
            $table->decimal('lng', 12, 8)->comment('经度');
            $table->decimal('Lat', 12, 8)->comment('纬度');
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
        Schema::drop('region');
    }
}
