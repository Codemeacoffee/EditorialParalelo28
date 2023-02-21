<?php

namespace Paralelo28;

use Illuminate\Database\Eloquent\Model;

class RefundTicket extends Model
{
    protected $fillable = [
        'shoppingHistoryId', 'reason', 'status', 'statusMessage'
    ];
}
