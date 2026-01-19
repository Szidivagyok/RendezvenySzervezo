<?php

namespace Database\Seeders;

use App\Helpers\CsvReader;
use App\Models\OrderService;
use Illuminate\Database\Seeder;

class OrderServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $fileName = 'csv/orderServices.csv';
        $delimeter = ';';
        $data = CsvReader::csvToArray($fileName,$delimeter);
        OrderService::factory()->createMany($data);
    }
}
