<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'certificate', 'imageLink', 'category'
    ];
}
