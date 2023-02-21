<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class BookCertificate extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'bookId', 'certificate'
        ];
}
