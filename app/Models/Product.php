<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'category', 'status', 'launch_date', 'vendor_name', 'vendor_link', 'image'
    ];
}
