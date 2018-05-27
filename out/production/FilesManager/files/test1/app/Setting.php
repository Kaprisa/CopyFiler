<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const FIELDS = [
        'input',
        'textarea',
        'image',
        'select'
    ];

    protected $fillable = [
        'name',
        'value',
        'field_type'
    ];
}
