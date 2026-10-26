<?php

namespace Database\Seeders;

use App\Models\Centre;
use Illuminate\Database\Seeder;

class CentreSeeder extends Seeder
{
    public function run()
    {
        $centres = [
            ['nom' => 'Centre Médical Pasteur', 'adresse' => '12 avenue de la République', 'telephone' => '+224 620 00 00 01', 'email' => 'contact@cm-pasteur.test'],
            ['nom' => 'Clinique Les Palmiers', 'adresse' => '45 boulevard du Commerce', 'telephone' => '+224 620 00 00 02', 'email' => 'accueil@clinique-palmiers.test'],
            ['nom' => 'Polyclinique Espoir', 'adresse' => '8 rue de la Santé', 'telephone' => '+224 620 00 00 03', 'email' => 'info@polyclinique-espoir.test'],
        ];

        foreach ($centres as $centre) {
            Centre::create($centre);
        }
    }
}
