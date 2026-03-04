<?php

namespace Database\Seeders;

use App\Helpers\CsvReader;
use App\Models\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $fileName = 'csv/service_types.csv';
        $delimeter = ';';
        $data = CsvReader::csvToArray($fileName,$delimeter);
        ServiceType::factory()->createMany($data);
    }
}
