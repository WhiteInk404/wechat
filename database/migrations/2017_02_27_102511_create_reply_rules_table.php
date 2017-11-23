<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReplyRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reply_rules', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('keyword', 50)->comment('关键词');
			$table->integer('type')->comment('类型');
			$table->integer('match_type')->comment('对方回复类型');
			$table->integer('reply_type')->comment('回复类型');
			$table->integer('mid')->unsigned()->default(0);
			$table->text('content', 65535)->nullable();
			$table->integer('status')->comment('状态');
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
		Schema::drop('reply_rules');
	}

}
