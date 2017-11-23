<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_categories', function ($table) {
            $table->increments('id');
            $table->tinyInteger('type');
            $table->string('name', 255)->comment('附件类型名称');
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
        //
        // Schema::table('doctot_patients', function ($table) {
        //     $table->enum('is_sign', array('yes','no'))->default('no')->after('patient_id')->comment('是否签约');
        // });
    }
}
