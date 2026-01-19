<?php

namespace Database\Seeders;

use App\Helpers\CsvReader;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
      $fileName = 'csv/order.csv';
        $delimeter = ';';
        $data = CsvReader::csvToArray($fileName,$delimeter);
        Order::factory()->createMany($data);
    }
}
