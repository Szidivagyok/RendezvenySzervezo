<?php

namespace Database\Seeders;

use App\Models\Picture;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationsPictureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $pictures = Picture::all();

        foreach ($pictures as $picture) {
            // Kinyerjük a locationId-t a fájlnévből (pl: "location_5.1.jpg" -> 5)
            // A "location_" utáni részt keressük az első pontig
            $nameParts = explode('_', $picture->pictureName);
            if (isset($nameParts[1])) {
                $locationIdPart = explode('.', $nameParts[1])[0];

                // Beillesztjük a kapcsolótáblába
                DB::table('locations_pictures')->insert([
                    'pictureId'  => $picture->id,
                    'locationId' => (int)$locationIdPart,
                ]);
            }
        }
    }
}
