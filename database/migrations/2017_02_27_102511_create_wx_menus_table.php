<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWxMenusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wx_menus', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->nullable()->comment('名称');
			$table->integer('type')->unsigned()->default(1)->comment('菜单类型');
			$table->string('menu_id', 30)->nullable()->comment('菜单ID');
			$table->text('button', 65535)->comment('菜单按钮');
			$table->text('matchrule', 65535)->comment('菜单匹配规则');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('wx_menus');
	}

}
