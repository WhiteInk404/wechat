<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWxUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wx_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned()->default(0)->comment('用户id');
			$table->string('openid', 50)->comment('用户 openid');
			$table->string('nickname', 250)->default('');
			$table->boolean('sex')->default(0);
			$table->boolean('subscribe')->default(0)->comment('是否订阅0为订阅,1已订阅');
			$table->string('avatar', 250)->default('');
			$table->string('city', 20)->default('')->comment('微信城市');
			$table->string('province', 20)->default('')->comment('微信省份');
			$table->string('language', 10)->default('')->comment('微信语言');
			$table->dateTime('subscribe_time')->nullable()->comment('微信关注时间');
			$table->string('remark')->default('')->comment('标记 其他');
			$table->integer('scene_id')->unsigned()->default(0)->comment('场景ID');
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
		Schema::drop('wx_users');
	}

}
