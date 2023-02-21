<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $fillable = [
        'question', 'type', 'order', 'survey'
    ];
}
