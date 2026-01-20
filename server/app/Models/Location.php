<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    protected $fillable = [
        'cityName',
        'zipCode',
        'street',
        'houseNumber',
        'locationName',
        'maxCapacity',
        'minCapacity',
        'priceSlashPerson',
        'roomPriceSlashDay'
    ];

     protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
