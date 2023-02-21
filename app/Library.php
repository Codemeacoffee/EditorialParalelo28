<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $fillable = [
        'userId', 'bookId', 'option'
    ];
}

