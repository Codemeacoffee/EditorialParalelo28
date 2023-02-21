<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    protected $fillable = [
        'userId', 'shipmentCode'
    ];
}
