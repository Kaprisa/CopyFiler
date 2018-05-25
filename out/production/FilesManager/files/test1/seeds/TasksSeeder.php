<?php

use App\TaskCategory;
use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskCategory::create([
            'name' => 'Начало',
            'icon' => 'beach_access',
            'color' => '#00e6e6',
            'sort' => 1
        ]);
        TaskCategory::create([
            'name' => 'В процессе',
            'icon' => 'trending_up',
            'color' => '#009900',
            'sort' => 2
        ]);
        TaskCategory::create([
            'name' => 'Тестирование',
            'icon' => 'sync_problem',
            'color' => '#ffff00',
            'sort' => 3
        ]);
        TaskCategory::create([
            'name' => 'Завершенные',
            'icon' => 'done',
            'color' => '#ff3300',
            'sort' => 4
        ]);
    }
}
