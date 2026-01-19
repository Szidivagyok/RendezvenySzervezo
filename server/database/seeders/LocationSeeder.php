<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { $data =
[
    [
        'cityName' => 'Szolnok',
        'zipCode' => '5052',
        'street' => 'Gőz street',
        'houseNumber' => '12',
        'locationName' => 'restaurant',
        'maxCapacity' => random_int(10, 100),
        'minCapacty' => random_int(10, 100),
        'priceSlashPerson' => 12000,
        'roomPriceSlashDay' => 24000,
    ],
     [
        'cityName' => 'Budapest',
        'zipCode' => '1007',
        'street' => 'Faludi street',
        'houseNumber' => '22',
        'locationName' => 'community hall',
        'maxCapacity' => random_int(30, 200),
        'minCapacty' => random_int(30, 200),
        'priceSlashPerson' => 17000,
        'roomPriceSlashDay' => 36000,
    ],
     [
        'cityName' => 'Székefehérvár',
        'zipCode' => '8000',
        'street' => 'Nagy street',
        'houseNumber' => '22',
        'locationName' => 'community hall',
        'maxCapacity' => random_int(30, 200),
        'minCapacty' => random_int(30, 200),
        'priceSlashPerson' => 17000,
        'roomPriceSlashDay' => 36000,
    ],
];
}
}