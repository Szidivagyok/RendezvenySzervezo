<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        do {
            $randomUserId = User::inRandomOrder()->first()->id ?? null;
            $randomLocationId = Location::inRandomOrder()->first()->id ?? null;
            $from = '2026-01-01 00:00:00';
            $to   = '2026-12-31 23:59:59';

            $randomDateTime = $this->faker->dateTimeBetween($from, $to);
            // Védelmi mechanizmus: ha nincsenek adatok a forrástáblákban, kilépünk.
            if (is_null($randomUserId) || is_null($randomLocationId)) {
                break;
            }

            // 2. Egyediség ellenőrzése a Playsports táblában
            $exists = Order::where('userId', $randomUserId)
                ->where('locationId', $randomLocationId)
                 ->where('orderTime', $randomDateTime)
                ->exists();
        } while ($exists);
        return [
            'userId' => $randomUserId,
            'locationId' => $randomLocationId,
            'orderTime' => $randomDateTime,
        ];
    }
}
