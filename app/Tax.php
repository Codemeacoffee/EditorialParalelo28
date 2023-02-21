<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'tax', 'format', 'amount'
    ];
}
