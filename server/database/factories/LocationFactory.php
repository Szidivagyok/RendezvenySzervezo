<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{

    protected function withFaker()
    {
        return \Faker\Factory::create('hu_HU');
    }
    public function definition(): array
    {

        return [
            // magyar városnév
            'cityName' => $this->faker->city(),

            // magyar irányítószám
            'zipCode' => $this->faker->postcode(),

            // magyar utcanév
            'street' => $this->faker->streetName(),

            // házszám
            'houseNumber' => $this->faker->numberBetween(1, 200),

            // helyszín neve (pl. cégnév)
            'locationName' => $this->faker->randomElement([
                'restaurant',
                'community hall',
                'church',
                'hotel',
                'castle',
            ]),


            // kapacitás
            'maxCapacity' => $this->faker->numberBetween(50, 300),
            'minCapacity' => $this->faker->numberBetween(10, 100),

            // árak
            'priceSlashPerson' => round(
                $this->faker->numberBetween(12000, 25000),

            ),

            'roomPriceSlashDay' => round(
                $this->faker->numberBetween(20000, 200000),

            ),
        ];
    }
}
