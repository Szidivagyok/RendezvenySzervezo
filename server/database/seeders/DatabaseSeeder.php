<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //Mielőtt seedelünk, minden táblát töröljünk le.
        DB::statement('DELETE FROM users');
        DB::statement('DELETE FROM orderServices');
        DB::statement('DELETE FROM orders');
        DB::statement('DELETE FROM services');
        DB::statement('DELETE FROM locations');



        //Ami Seeder osztály itt fel van sorolva, annak lefut a run() metódusa
        $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
            OrderServiceSeeder::class,
            OrderSeeder::class,
            LocationSeeder::class,
            
        ]);
    }
}
