<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data =
[
    [
        'service' => 'live music',
        'price' => 70000,

    ],
    [
        'service' => 'dj',
        'price' => 50000,

    ],
    [
        'service' => 'two-course',
        'price' => 6000,

    ],
    [
        'service' => 'three-course',
        'price' => 10000,

    ],
    [
        'service' => 'four-course',
        'price' => 15000,

    ],
    [
        'service' => 'simple',
        'price' => 100000,

    ],
    [
        'service' => 'medium',
        'price' => 150000,

    ],
    [
        'service' => 'decorative',
        'price' => 200000,

    ],
];
    }
}
