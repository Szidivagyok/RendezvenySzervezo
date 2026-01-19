<?php

namespace Database\Seeders;

use App\Helpers\CsvReader;
use Database\Factories\ServiceFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $fileName = 'csv/schoolclasses.csv';
        $delimeter = ';';
        $data = CsvReader::csvToArray($fileName,$delimeter);
        ServiceFactory::factory()->createMany($data);

    }
}
