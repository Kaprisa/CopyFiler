<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'colors',
        'name',
        'dark'
    ];

    public function getColorsAttribute($val)
    {
        return json_decode($val);
    }
}
