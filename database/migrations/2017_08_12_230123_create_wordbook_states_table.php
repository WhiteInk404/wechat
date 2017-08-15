<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordbookStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wordbook_states', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('wordbook_id');
            $table->integer('word_total');
            $table->integer('remember_total')->default(0);
            $table->integer('remembered_wordbook_total')->default(0);
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
        Schema::drop('wordbook_states');
    }
}
