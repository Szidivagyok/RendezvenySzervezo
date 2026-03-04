<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationsPicture extends Model
{
    /** @use HasFactory<\Database\Factories\LocationsPictureFactory> */
    use HasFactory;

       protected $fillable = [
        'pictureId',
        'locationId',
    ];

     protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
