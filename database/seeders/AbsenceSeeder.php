<?php

namespace Database\Seeders;

use App\Models\Absence;
use App\Models\Medecin;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    /**
     * Absences de démonstration calculées à partir de la date du jour, pour
     * qu'il y ait toujours des cas à tester quel que soit le moment du seed.
     *
     * Les absences existantes sont remplacées : le seeder peut être relancé
     * à chaque démarrage en production (docker/start.sh) pour garder des
     * dates à jour, sans créer de doublons. L'application n'ayant pas d'écran
     * de gestion des absences, aucune donnée saisie n'est perdue.
     */
    public function run()
    {
        Absence::query()->delete();

        $aujourdhui = Carbon::today();

        $absences = [
            ['Diallo', $aujourdhui->copy(), $aujourdhui->copy()->addDays(2), 'maladie'],
            ['Sylla', $aujourdhui->copy()->addWeek()->startOfWeek(), $aujourdhui->copy()->addWeek()->endOfWeek(), 'conges'],
            ['Condé', $aujourdhui->copy()->nextWeekday(), $aujourdhui->copy()->nextWeekday(), 'autre'],
        ];

        foreach ($absences as [$nom, $debut, $fin, $motif]) {
            Medecin::where('nom', $nom)->firstOrFail()->absences()->create([
                'date_debut' => $debut->toDateString(),
                'date_fin' => $fin->toDateString(),
                'motif' => $motif,
            ]);
        }
    }
}
