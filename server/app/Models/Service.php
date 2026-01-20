<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;
      protected $fillable = [
      'service',
      'price',
      'created_at',
      'updated_at', 
      ];

       protected $hidden =[
        'created_at',
        'updated_at',
    ];
}
