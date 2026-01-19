<?php

namespace Database\Seeders;

use App\Helpers\CsvReader;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $fileName = 'csv/services.csv';
        $delimeter = ';';
        $data = CsvReader::csvToArray($fileName,$delimeter);
        Service::factory()->createMany($data);

    }
}
