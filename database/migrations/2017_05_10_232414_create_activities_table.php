<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('begin_time')->nullable()->comment('活动开始日期');
            $table->timestamp('end_time')->nullable()->comment('活动结束日期');
            $table->string('pic_url')->comment('海报');
            $table->string('labels')->unique()->comment('标签规则，英文逗号分隔');
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
        Schema::drop('activities');
    }
}
