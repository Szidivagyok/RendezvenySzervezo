<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function locations(): BelongsToMany
    {
        // Paraméterek: 
        // 1. Kapcsolódó modell
        // 2. Pivot tábla neve
        // 3. Foreign key ebben a modellben (pictureId)
        // 4. Foreign key a cél modellben (locationId)
        return $this->belongsToMany(Location::class, 'locations_pictures', 'pictureId', 'locationId');
    }
}
