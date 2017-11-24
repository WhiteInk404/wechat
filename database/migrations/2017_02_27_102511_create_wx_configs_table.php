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
			$table->string('wechat_id')->default('')->comment('微信ID');
			$table->string('source_id', 20)->default('')->comment('微信原始ID');
			$table->string('name', 30)->default('')->comment('微信公众号名称');
			$table->string('appid')->default('')->comment('appid');
			$table->string('app_secret')->default('');
			$table->string('token')->default('');
			$table->string('mch_id', 32)->default('')->comment('商户ID');
			$table->string('sign_key', 128)->default('')->comment('签名key');
			$table->string('extra')->default('')->comment('其他参数');
            $table->string('aes_key')->default('')->comment('消息加解密密钥');
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
