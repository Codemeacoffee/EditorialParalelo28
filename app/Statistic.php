<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $fillable = [
        'bookId', 'physicalSales', 'digitalSales', 'addedToWishList', 'addedToCart'
    ];
}
