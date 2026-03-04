<?php

namespace Database\Seeders;

use App\Models\Picture;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PictureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $locationIds = Location::pluck('id');

        foreach ($locationIds as $locId) {
            // Helyszínenként 3 kép generálása
            for ($i = 1; $i <= 3; $i++) {
                Picture::create([
                    'PictureName' => "location_{$locId}.{$i}.jpg",
                    'serviceId'   => 1
                ]);
            }
        }
    }
}
