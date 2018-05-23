<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text');
            $table->enum('type', array_pluck(\App\Question::Types, 'value'));//['test', 'task', 'matching']
            $table->string('controller')->nullable(); // Проверка задачи
            $table->text('answer')->nullable();
            $table->text('comment')->nullable();
            $table->integer('points')->unsigned(); // баллы
            $table->integer('lesson_id')->unsigned()->references('id')->on('lessons')->onDelete('cascade');;
            $table->integer('sort');
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('questions');
    }
}
