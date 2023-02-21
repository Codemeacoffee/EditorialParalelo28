<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'shipmentCode', 'bookId', 'price', 'couponUsed', 'option', 'amount'
    ];
}
