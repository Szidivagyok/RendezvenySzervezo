<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    /** @use HasFactory<\Database\Factories\PictureFactory> */
    use HasFactory;


     protected $fillable = [
        'pictureName',
        'serviceId',
    ];

     protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
