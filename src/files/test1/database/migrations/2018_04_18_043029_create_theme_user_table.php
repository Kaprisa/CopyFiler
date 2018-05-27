<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_user', function (Blueprint $table) {
            $table->integer('theme_id')->unsigned()->references('id')->on('themes')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->onDelete('cascade');
            $table->primary(['theme_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theme_user');
    }
}
