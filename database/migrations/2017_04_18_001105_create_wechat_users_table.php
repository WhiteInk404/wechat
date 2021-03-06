<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWechatUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('`users`.`id`');
            $table->char('openid', 28)->comment('openid');
            $table->string('nickname', 100)->default('')->comment('nickname');
            $table->boolean('gender')->default(0)->comment('0 未知 1 男 2 女');
            $table->string('city', 30)->default('')->comment('城市');
            $table->string('province', 30)->default('')->comment('省份');
            $table->string('country', 30)->default('')->comment('国家');
            $table->string('avatar_url')->default('')->comment('微信头像地址');
            $table->char('union_id', 28)->default('')->comment('union id');
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
        Schema::drop('wechat_users');
    }
}
