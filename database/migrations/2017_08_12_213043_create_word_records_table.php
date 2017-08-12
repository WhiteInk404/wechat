<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('word_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('wordbook_id');
            $table->integer('wordbook_content_id');
            $table->tinyInteger('status')->default(1)->comment('0 不认识 1 认识 2 模糊');
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
        Schema::drop('word_records');
    }
}
