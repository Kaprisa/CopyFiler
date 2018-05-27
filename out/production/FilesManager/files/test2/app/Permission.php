<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    const ACTIONS = [
        [
            'text' => 'Просмотр',
            'value' => 'read'
        ],
        [
            'text' => 'Редактирование',
            'value' => 'write'
        ],
        [
            'text' => 'Удаление',
            'value' => 'delete'
        ]
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
