<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskCategory extends Model
{
    protected $fillable =[
        'name',
        'icon',
        'color',
        'sort'
    ];

    protected $with = ['tasks'];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'category_id');
    }
}
