<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'userId', 'name', 'surnames', 'direction', 'postalCode', 'taxes', 'creditCard'
    ];

    protected $hidden = [
        'creditCard'
    ];
}
