<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CentreSeeder::class,
            SpecialiteSeeder::class,
            MedecinSeeder::class,
            AbsenceSeeder::class,
            UserSeeder::class,
        ]);
    }
}
