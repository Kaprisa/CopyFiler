<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'status'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
