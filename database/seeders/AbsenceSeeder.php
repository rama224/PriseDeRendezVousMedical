<?php

namespace Database\Seeders;

use App\Models\Medecin;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AbsenceSeeder extends Seeder
{
    /**
     * Absences calculées à partir de la date du jour, pour qu'il y ait
     * toujours des cas à tester quel que soit le moment du seed.
     */
    public function run()
    {
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
