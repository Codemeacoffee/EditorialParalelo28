<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class BlogEntry extends Model
{
    protected $fillable = [
        'title', 'category', 'content', 'imgLink'
    ];
}
