<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{

    protected $fillable = [
        'name',
        'description',
        'image',
        'category_id',
        'active',
        'user_id'
    ];

    protected $appends = [
        'editable',
        'studied',
        'points',
        'user_points'
    ];

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getEditableAttribute()
    {
        $user = Auth::guard('api')->user();
        return $user ? ($this->user->id === $user->id || $user->hasRole('admin')) : false;
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function getStudiedAttribute()
    {
        return Auth::guard('api')->user()->courses()->pluck('id')->contains($this->id);
    }

    public function getQuestionsAttribute()
    {
        return Lesson::whereIn('block_id', $this->blocks()->pluck('id'))
            ->get()
            ->pluck('questions')
            ->flatten();
    }

    public function getPointsAttribute()
    {
        return $this->questions->sum('points');
    }

    public function getAttemptsAttribute()
    {
        return $this->questions->sum('attempts');
    }

    public function getUserPointsAttribute()
    {
        return $this->questions->where('correct', true)->sum('points');
    }
}
