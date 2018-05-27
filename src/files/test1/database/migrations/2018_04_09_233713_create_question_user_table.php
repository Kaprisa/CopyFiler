<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_user', function (Blueprint $table) {
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->integer('attempts')->default(0);
            $table->boolean('correct')->default(0);
            $table->primary(['user_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_questions');
    }
}
