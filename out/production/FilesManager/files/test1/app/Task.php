<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'description',
        'name',
        'executor_id',
        'creator_id',
        'category_id'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function executor()
    {
        return $this->belongsTo(User::class, 'executor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function category()
    {
        return $this->belongsTo(TaskCategory::class, 'category_id');
    }
}
