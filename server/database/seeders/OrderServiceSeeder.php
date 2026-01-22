<?php

namespace Database\Seeders;

use App\Helpers\CsvReader;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\Service;
use Illuminate\Database\Seeder;

class OrderServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $numberOfOrders = 30;

    for ($i = 0; $i < $numberOfOrders; $i++) {
      OrderService::factory()->create();
    }
}
}