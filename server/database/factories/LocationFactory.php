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
            'cityname' => $this->faker->unique()->randomElement([
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

            'zipcode' => $this->faker->numberBetween(1000, 9999),
            'street' => $this->faker->streetName(),
            'housenumber' => $this->faker->numberBetween(1, 200),
            'locationname' => $this->faker->company(),

            'maxcapicity' => $this->faker->numberBetween(50, 500),
            'mincapacity' => $this->faker->numberBetween(10, 49),

            'priceslashperson' => $this->faker->numberBetween(2000, 15000),
            'roompriceslashday' => $this->faker->numberBetween(20000, 150000),
        ];
    }
}
