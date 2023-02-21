<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'email', 'token', 'created_at'
    ];

    protected $hidden = [
        'token'
    ];
}
