<?php

namespace Paralelo28;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'email', 'password', 'email_verify', 'email_verify_date', 'accountType', 'companyName', 'companyCIF', 'admin', 'session_token'
    ];

    protected $hidden = [
        'password', 'admin', 'session_token'
    ];
}
