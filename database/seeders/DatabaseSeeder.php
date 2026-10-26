<?php

namespace Database\Seeders;

use App\Models\Centre;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Le jeu de données n'est chargé que si la base est vide : le seed peut
     * ainsi être lancé à chaque démarrage en production sans créer de doublons.
     *
     * @return void
     */
    public function run()
    {
        if (Centre::exists()) {
            $this->command->info('Données déjà présentes : seed ignoré.');

            return;
        }

        $this->call([
            CentreSeeder::class,
            SpecialiteSeeder::class,
            MedecinSeeder::class,
            AbsenceSeeder::class,
            UserSeeder::class,
        ]);
    }
}
