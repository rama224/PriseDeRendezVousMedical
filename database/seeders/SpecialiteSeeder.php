<?php

namespace Database\Seeders;

use App\Models\Specialite;
use Illuminate\Database\Seeder;

class SpecialiteSeeder extends Seeder
{
    public function run()
    {
        $specialites = ['Médecine générale', 'Cardiologie', 'Pédiatrie', 'Dermatologie', 'Gynécologie'];

        foreach ($specialites as $nom) {
            Specialite::create(['nom' => $nom]);
        }
    }
}
