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
        do {
            $randomOrderId = Order::inRandomOrder()->first()->id ?? null;
            $randomServiceId = Service::inRandomOrder()->first()->id ?? null;


            // Védelmi mechanizmus: ha nincsenek adatok a forrástáblákban, kilépünk.
            if (is_null($randomOrderId) || is_null($randomServiceId)) {
                break;
            }

            // 2. Egyediség ellenőrzése a Playsports táblában
            $exists = OrderFactory::where('orderId', $randomOrderId)
                ->where('serviceId', $randomServiceId)
                ->exists();
        } while ($exists);
        return [
            'orderId' => $randomOrderId,
            'serviceId' => $randomServiceId,
        ];
    }
}
