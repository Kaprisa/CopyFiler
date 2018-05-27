<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone'
    ];

    protected $with = ['roles', 'theme'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'points',
        'is_admin',
        //'my_courses',
        'analysis'
    ];

    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class)->withPivot(['attempts', 'correct']);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        return $this->roles()->pluck('name')->contains($role);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class)->withPivot(['percent']);
    }

    public function getPointsAttribute()
    {
        return $this->questions()->withPivot(['correct'])->where('correct', true)->sum('points');
    }

    public function getAnalysisAttribute()
    {
        $questions = $this->questions();
        return [
           'attempts' => (int)$questions->sum('attempts'),
           'count' => $questions->count(),
           'points' => (int)$questions->where('correct', true)->sum('points'),
           'correct'=> $questions->where('correct', true)->count()
        ];
        //return $this->questions()->withPivot(['correct'])->where('correct', true)->sum('points');
    }

    public function getIsAdminAttribute()
    {
        return $this->hasRole('admin');
    }

    public function getMyCoursesAttribute() {
        return 'hello';
//       $courses = $this->courses()->get();
//       return $courses->map(function ($c) {
//            return collect($c->toArray())
//                ->only(['name', 'points', 'attempts'])
//                ->all();
//        });
//        return $this->courses()->get()
    }

    public function theme() {
        return $this->belongsTo(Theme::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'executor_id');
    }

}
