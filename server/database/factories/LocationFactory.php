<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
            //
            return [
            'cityName' => $this->faker->unique()->randomElement([
                'Budapest',
                'Debrecen',
                'Szeged',
                'Miskolc',
                'Pécs',
                'Győr',
                'Nyíregyháza',
                'Kecskemét',
                'Székesfehérvár',
                'Szombathely',
                'Érd',
                'Tatabánya',
                'Sopron',
                'Veszprém',
                'Zalaegerszeg',
                'Eger',
                'Kaposvár',
                'Békéscsaba',
                'Dunaújváros',
                'Siófok',
            ]),

            'zipCode' => $this->faker->numberBetween(1000, 9999),
            'street' => $this->faker->streetName(),
            'houseNumber' => $this->faker->numberBetween(1, 100),
            'locationName' => $this->faker->company(),

            'maxCapacity' => $this->faker->numberBetween(50, 200),
            'minCapacity' => $this->faker->numberBetween(10, 49),

            'priceSlashPerson' => $this->faker->numberBetween(12000, 17000),
            'roomPriceSlashDay' => $this->faker->numberBetween(20000, 150000),
        ];
    }
}
