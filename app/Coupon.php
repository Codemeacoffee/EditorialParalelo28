<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'discount', 'uses', 'valid_until'
    ];
}
