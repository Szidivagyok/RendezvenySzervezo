<?php

namespace Database\Seeders;

use App\Helpers\CsvReader;
use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        // $fileName = 'csv/locations.csv';
        // $delimeter = ';';
        // $data = CsvReader::csvToArray($fileName,$delimeter);
        // Location::factory()->createMany($data);
}
}