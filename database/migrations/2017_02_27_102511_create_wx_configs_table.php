<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWxConfigsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wx_configs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('wechat_id')->comment('微信ID');
			$table->string('source_id', 20)->comment('微信原始ID');
			$table->string('name', 30)->comment('微信公众号名称');
			$table->string('appid')->comment('appid');
			$table->string('app_secret');
			$table->string('token');
			$table->string('mch_id', 32)->default('')->comment('商户ID');
			$table->string('sign_key', 128)->default('')->comment('签名key');
			$table->string('extra')->comment('其他参数');
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
		Schema::drop('wx_configs');
	}

}
