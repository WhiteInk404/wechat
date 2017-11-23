<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWxMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wx_messages', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('to', 65535)->comment('消息发送对象');
			$table->boolean('type')->comment('消息类型');
			$table->text('content', 65535)->comment('消息类型');
			$table->boolean('status')->comment('发送状态');
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
		Schema::drop('wx_messages');
	}

}
