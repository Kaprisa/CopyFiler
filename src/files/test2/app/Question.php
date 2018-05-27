<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Question extends Model
{

    protected $appends = ['attempts', 'correct', 'answers'];

    const Types = [
        [
            'text' => 'Задача',
            'value' => 'task'
        ],
        [
            'text' => 'Тест',
            'value' => 'test'
        ],
        [
            'text' => 'Тест (один ответ)',
            'value' => 'test_one'
        ],
        [
            'text' => 'Сопоставление',
            'value' => 'matching'
        ]
    ];

    protected $fillable = [
        'text',
        'type',
        'controller',
        'answer',
        'comment',
        'points',
        'lesson_id',
        'active',
        'sort',
        'lesson_id'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot(['attempts', 'correct']);
    }

    public function getAnswersAttribute()
    {
        if ($this->type == 'test' || $this->type === 'test_one') {
            return json_decode($this->answer);
        }
        return [];
    }

    public function getAttemptsAttribute()
    {
        $e = Auth::guard('api')
            ->user()
            ->questions()
            ->find($this->id);

        if ($e) {
            return $e
                ->pivot
                ->attempts;
        }

        return null;
    }

    public function getCorrectAttribute()
    {
        $e = Auth::guard('api')
            ->user()
            ->questions()
            ->find($this->id);

        if ($e) {
            return $e
                ->pivot
                ->correct;
        }

        return null;
    }
}
