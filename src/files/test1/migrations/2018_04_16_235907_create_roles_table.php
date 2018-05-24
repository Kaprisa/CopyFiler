<?php

use App\Role;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->timestamps();
        });

        Artisan::call('db:seed', ['--class' => RolesSeeder::class]);
//        $names = [
//            'admin' => 'Администратор',
//            'user' => 'Пользователь',
//            'teacher' => 'Учитель',
//            'student' => 'Ученик'
//        ];
//
//        foreach ($names as $n => $dn) {
//            Role::create([
//                'name' => $n,
//                'display_name' => $dn
//            ]);
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
