<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'affiliationName', 'imgSrc'
    ];
}
