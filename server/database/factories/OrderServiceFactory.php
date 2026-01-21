<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderService>
 */
class OrderServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
             // random létező orderId kiválasztása
            'orderId' => Order::inRandomOrder()->first()->id ?? 1,  

            // random létező serviceId kiválasztása
            'serviceId' => Service::inRandomOrder()->first()->id ?? 1, 
        ];
    }
}
