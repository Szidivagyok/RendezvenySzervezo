<?php

namespace Database\Seeders;

use App\Helpers\CsvReader;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    //
    $numberOfOrders = 50;

    for ($i = 0; $i < $numberOfOrders; $i++) {
      Order::factory()->create();
    }
  }
}
