<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
   
    protected $fillable = [
        'title', 'author', 'description', 'measures', 'pages', 'language', 'isbn', 'bookbinding', 'edition', 'physicalPrice', 'digitalPrice', 'discount', 'stock', 'previewImage', 'images', 'file', 'promoted'
    ];
}
