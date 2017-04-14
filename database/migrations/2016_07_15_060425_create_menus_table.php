<?php

use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->unsigned()->nullable()->comment('父菜单ID');
            $table->string('name')->comment('菜单名');
            $table->enum('state', ['open', 'closed'])->default('open')->comment('菜单状态：打开，关闭');
            $table->string('icon')->comment('菜单图标');
            $table->string('url')->comment('菜单链接');
            $table->timestamps();
            $table->foreign('pid')->references('id')->on('menus');
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('menus');
	}
}
