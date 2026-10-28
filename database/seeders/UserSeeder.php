<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /** Compte de démonstration ; le mot de passe respecte la règle de l'inscription. */
    public const EMAIL = 'patient@test.com';
    public const MOT_DE_PASSE = 'Patient123!';

    /**
     * Crée ou remet en état le compte de démonstration : le seeder peut être
     * relancé à chaque démarrage en production (docker/start.sh).
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => self::EMAIL],
            [
                'name' => 'Patient Test',
                'password' => Hash::make(self::MOT_DE_PASSE),
            ]
        );
    }
}
