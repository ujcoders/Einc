<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductEmailLog extends Model
{
    protected $fillable = ['product_id', 'recipient_email', 'subject', 'body', 'sent_at'];
}
