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
           $orders = Order::all();    // létező rendelések
        $services = Service::all(); // létező szolgáltatások
 
        foreach ($orders as $order) {
            // Minden orderhez 1-5 random service
            $count = rand(1, 5);
            $serviceIds = $services->random($count)->pluck('id')->toArray();
 
            foreach ($serviceIds as $serviceId) {
                OrderService::create([
                    'orderId' => $order->id,
                    'serviceId' => $serviceId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    }
}
    }
}