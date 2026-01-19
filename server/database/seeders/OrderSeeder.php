<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
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
        'userId' => 1,2,
        'category' => 'Bogyós',
        'name' => 'Málna',
        'description' => 'Kézzel termelt egészség',
        'picture' => 'https://hur.webmania.cc/img/malna.jpg',
        'price' => 3800,
        'stock' => 500,
    ],
];
    }
}
