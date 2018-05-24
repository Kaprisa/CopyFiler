<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodeLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text');
            $table->text('answer');
            $table->text('task');
            $table->string('controller');
            $table->string('lang');
            $table->integer('points');
            $table->text('hints');
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
        Schema::dropIfExists('code_lessons');
    }
}
