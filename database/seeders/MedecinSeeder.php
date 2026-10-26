<?php

namespace Database\Seeders;

use App\Models\Centre;
use App\Models\Medecin;
use App\Models\Specialite;
use Illuminate\Database\Seeder;

class MedecinSeeder extends Seeder
{
    /** Horaires standards : du lundi au vendredi, 8h-12h et 14h-17h. */
    private const HORAIRES_STANDARDS = [
        'jours' => [1, 2, 3, 4, 5],
        'plages' => [['08:00', '12:00'], ['14:00', '17:00']],
    ];

    public function run()
    {
        $centres = Centre::pluck('id', 'nom');
        $specialites = Specialite::pluck('id', 'nom');

        $medecins = [
            ['Camara', 'Mariama', 'Médecine générale', 'Centre Médical Pasteur'],
            ['Diallo', 'Ibrahima', 'Cardiologie', 'Centre Médical Pasteur'],
            ['Bah', 'Fatoumata', 'Pédiatrie', 'Centre Médical Pasteur'],
            ['Sylla', 'Mamadou', 'Médecine générale', 'Clinique Les Palmiers'],
            ['Kouyaté', 'Aïssatou', 'Dermatologie', 'Clinique Les Palmiers'],
            ['Touré', 'Sékou', 'Cardiologie', 'Clinique Les Palmiers'],
            ['Barry', 'Hadja', 'Gynécologie', 'Clinique Les Palmiers'],
            ['Condé', 'Alpha', 'Médecine générale', 'Polyclinique Espoir'],
            ['Soumah', 'Kadiatou', 'Pédiatrie', 'Polyclinique Espoir'],
            ['Keïta', 'Moussa', 'Gynécologie', 'Polyclinique Espoir'],
        ];

        // Quelques médecins ont des horaires différents des horaires standards.
        $horairesParticuliers = [
            'Bah' => ['jours' => [1, 3, 5], 'plages' => [['09:00', '13:00']]],
            'Kouyaté' => ['jours' => [2, 4, 6], 'plages' => [['08:30', '12:30']]],
        ];

        foreach ($medecins as [$nom, $prenom, $specialite, $centre]) {
            $medecin = Medecin::create([
                'nom' => $nom,
                'prenom' => $prenom,
                'specialite_id' => $specialites[$specialite],
                'centre_id' => $centres[$centre],
            ]);

            $horaires = $horairesParticuliers[$nom] ?? self::HORAIRES_STANDARDS;

            foreach ($horaires['jours'] as $jour) {
                foreach ($horaires['plages'] as [$debut, $fin]) {
                    $medecin->disponibilites()->create([
                        'jour_semaine' => $jour,
                        'heure_debut' => $debut,
                        'heure_fin' => $fin,
                    ]);
                }
            }
        }
    }
}
