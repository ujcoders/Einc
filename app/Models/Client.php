<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'CLIENT_ID',
        'client_name',
        'EMAIL_ID',
        'MOBILE_NO',
        'RESI_ADDRESS',
        'Max_Brokerage',
        'brk',
    ];
}
