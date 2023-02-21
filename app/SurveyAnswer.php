<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $fillable = [
        'question', 'answer', 'ip'
    ];

    protected $hidden = [
        'ip'
    ];
}
