<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class SurveyPossibleAnswer extends Model
{
    protected $fillable = [
        'surveyId', 'possibleAnswer'
    ];
}
