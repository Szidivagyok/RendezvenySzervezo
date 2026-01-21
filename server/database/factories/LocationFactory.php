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
            'street' => $this->faker->randomElement([
                'Gőz utca',
                'Petőfi Sándor utca',
                'Kossuth Lajos utca',
                'Rákóczi Ferenc utca',
                'Ady Endre utca',
                'Széchenyi István utca',
                'Arany János utca',
                'József Attila utca',
                'Dózsa György utca',
                'Béke utca',
                'Táncsics Mihály utca',
                'Damjanich utca',
                'Vörösmarty utca',
                'Bartók Béla út',
                'Móricz Zsigmond utca',
                'Hunyadi utca',
                'Kazinczy utca',
                'Deák Ferenc utca',
                'Fő utca',
                'Károly körút',
            ]),
            'houseNumber' => $this->faker->numberBetween(1, 100),
            'locationName' => $this->faker->randomElement([
                'restaurant',
                'community hall',
                'church',
                'hotel',
                'castle',
            ]),

            'maxCapacity' => $this->faker->numberBetween(50, 200),
            'minCapacity' => $this->faker->numberBetween(10, 49),

            'priceSlashPerson' => $this->faker->numberBetween(12000, 17000),
            'roomPriceSlashDay' => $this->faker->numberBetween(20000, 150000),
        ];
    }
}
