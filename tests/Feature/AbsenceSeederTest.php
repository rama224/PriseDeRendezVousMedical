<?php

namespace Tests\Feature;

use App\Models\Absence;
use Carbon\Carbon;
use Database\Seeders\AbsenceSeeder;
use Database\Seeders\CentreSeeder;
use Database\Seeders\MedecinSeeder;
use Database\Seeders\SpecialiteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsenceSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([CentreSeeder::class, SpecialiteSeeder::class, MedecinSeeder::class]);
    }

    /**
     * Périodes d'absence par motif, au format "date_debut → date_fin".
     */
    private function periodes()
    {
        return Absence::orderBy('motif')->get()->mapWithKeys(function (Absence $absence) {
            return [$absence->motif => $absence->date_debut->toDateString() . ' → ' . $absence->date_fin->toDateString()];
        })->all();
    }

    public function test_les_absences_partent_de_la_date_du_jour()
    {
        // Mardi 8 janvier 2030
        $this->travelTo(Carbon::parse('2030-01-08 07:00'));

        $this->seed(AbsenceSeeder::class);

        $this->assertSame([
            'autre' => '2030-01-09 → 2030-01-09',
            'conges' => '2030-01-14 → 2030-01-20',
            'maladie' => '2030-01-08 → 2030-01-10',
        ], $this->periodes());
    }

    public function test_relancer_le_seeder_remet_les_dates_a_jour_sans_doublons()
    {
        $this->travelTo(Carbon::parse('2030-01-08 07:00'));
        $this->seed(AbsenceSeeder::class);

        // Mardi 5 février 2030
        $this->travelTo(Carbon::parse('2030-02-05 07:00'));
        $this->seed(AbsenceSeeder::class);

        $this->assertSame(3, Absence::count());
        $this->assertSame([
            'autre' => '2030-02-06 → 2030-02-06',
            'conges' => '2030-02-11 → 2030-02-17',
            'maladie' => '2030-02-05 → 2030-02-07',
        ], $this->periodes());
    }
}
