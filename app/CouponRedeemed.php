<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class CouponRedeemed extends Model
{
    protected $fillable = [
        'userId', 'couponCode'
    ];
}
