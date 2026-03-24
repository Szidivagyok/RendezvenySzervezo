<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'userId',
        'locationId',
        'howManyPeople',
        'howManyDays',
        'orderTime',
        'is_system' // <--- Ezt adtuk hozzá
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Ezt is érdemes betenni, hogy a kódodban true/false legyen az értéke
    protected $casts = [
        'is_system' => 'boolean',
        'orderTime' => 'datetime'
    ];
}