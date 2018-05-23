<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'admin' => 'Администратор',
            'user' => 'Пользователь',
            'teacher' => 'Учитель',
            'student' => 'Ученик'
        ];

        foreach ($names as $n => $dn) {
           Role::create([
               'name' => $n,
               'display_name' => $dn
           ]);
        }
    }
}
