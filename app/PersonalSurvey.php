<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class PersonalSurvey extends Model
{
    protected $fillable = [
        'userId', 'shipmentCode', 'surveyUrl'
    ];
}
