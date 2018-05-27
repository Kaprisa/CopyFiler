<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{

    protected $fillable = [
        'course_id',
        'name',
        'active',
        'sort'
    ];

    protected $with = ['lessons'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
