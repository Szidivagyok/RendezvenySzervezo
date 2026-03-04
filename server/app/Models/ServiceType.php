<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceTypeFactory> */
    use HasFactory;

       protected $fillable = [
        'serviceTypeName',
    ];

     protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
