<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLead extends Model
{
    protected $fillable = ['product_id', 'name', 'email', 'phone', 'source', 'notes'];
}
