<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class ShoppingHistory extends Model
{
    protected $fillable = [
        'userId', 'shipmentCode', 'price', 'status', 'details', 'authorisationCode',
        'shipmentName', 'shipmentSurnames','shipmentAddress', 'shipmentPostCode',
        'billingName', 'billingSurnames','billingAddress', 'billingPostCode'
    ];
}
