<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $fillable = [
        'ip', 'tries'
    ];

    protected $hidden = [
        'ip',
    ];
}
