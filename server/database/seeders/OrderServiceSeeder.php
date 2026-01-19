<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderServiceSeeder extends Seeder
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
                    'category' => 1,
                    'name' => 'Málna',
                    'description' => 'Kézzel termelt egészség',
                    'picture' => 'https://hur.webmania.cc/img/malna.jpg',
                    'price' => 3800,
                    'stock' => 500,
                ],
            ];
    }
}
