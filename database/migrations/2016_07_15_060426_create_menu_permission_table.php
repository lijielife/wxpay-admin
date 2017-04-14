<?php

use Illuminate\Database\Migrations\Migration;

class CreateMenuPermissionTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('menu_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned()->index()->comment('菜单ID');
            $table->integer('permission_id')->unsigned()->index()->comment('角色ID');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('menu_permission');
	}
}
