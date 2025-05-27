<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'to_email',
        'user_name',
        'server_name',
        'ip_address',
        'host',
        'sent_at',
        'read_at',
        'bounced',
        'delivered',
        'status',
        'error_message'
    ];
}
