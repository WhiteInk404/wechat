<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWxQrcodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wx_qrcodes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('scene_id')->unsigned()->comment('场景ID');
			$table->integer('type')->unsigned()->default(0)->comment('二维码类型');
			$table->integer('type_id')->unsigned()->default(0)->comment('类型关联表ID');
			$table->string('remark')->comment('二维码描述');
			$table->string('ticket')->nullable()->comment('微信二维码');
			$table->string('url')->nullable();
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
		Schema::drop('wx_qrcodes');
	}

}
