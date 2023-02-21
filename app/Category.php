<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'category', 'imageLink', 'sampleBookImage'
    ];
}
