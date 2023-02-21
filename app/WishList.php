<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'userId', 'bookId'
    ];
}

