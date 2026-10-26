<?php

namespace Tests\Unit;

use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\User;
use App\Services\CreneauService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreneauServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @var CreneauService */
    private $service;

    /** @var Medecin */
    private $medecin;

    /** Lundi 7 janvier 2030. */
    private $lundi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->lundi = Carbon::parse('2030-01-07');
        $this->travelTo($this->lundi->copy()->setTime(7, 0));

        $this->service = new CreneauService();
        $this->medecin = Medecin::factory()->disponible(1, '08:00', '10:00')->create();
    }

    private function heures(array $creneaux)
    {
        return array_map(function (Carbon $creneau) {
            return $creneau->format('H:i');
        }, $creneaux);
    }

    public function test_decoupe_les_plages_de_disponibilite_en_creneaux_de_30_minutes()
    {
        $creneaux = $this->service->creneauxLibres($this->medecin, $this->lundi);

        $this->assertSame(['08:00', '08:30', '09:00', '09:30'], $this->heures($creneaux));
    }

    public function test_aucun_creneau_un_jour_sans_disponibilite()
    {
        $mardi = $this->lundi->copy()->addDay();

        $this->assertSame([], $this->service->creneauxLibres($this->medecin, $mardi));
    }

    public function test_aucun_creneau_pendant_une_absence()
    {
        $this->medecin->absences()->create([
            'date_debut' => '2030-01-05',
            'date_fin' => '2030-01-08',
            'motif' => 'conges',
        ]);

        $this->assertSame([], $this->service->creneauxLibres($this->medecin, $this->lundi));
    }

    public function test_une_absence_un_autre_jour_ne_bloque_pas_les_creneaux()
    {
        $this->medecin->absences()->create([
            'date_debut' => '2030-01-08',
            'date_fin' => '2030-01-10',
            'motif' => 'maladie',
        ]);

        $this->assertCount(4, $this->service->creneauxLibres($this->medecin, $this->lundi));
    }

    public function test_exclut_les_creneaux_deja_reserves_mais_pas_les_annules()
    {
        $user = User::factory()->create();
        $user->rendezVous()->create(['medecin_id' => $this->medecin->id, 'date_heure' => '2030-01-07 08:30:00']);
        $user->rendezVous()->create([
            'medecin_id' => $this->medecin->id,
            'date_heure' => '2030-01-07 09:00:00',
            'statut' => RendezVous::STATUT_ANNULE,
        ]);

        $creneaux = $this->service->creneauxLibres($this->medecin, $this->lundi);

        $this->assertSame(['08:00', '09:00', '09:30'], $this->heures($creneaux));
    }

    public function test_exclut_les_creneaux_deja_passes()
    {
        $this->travelTo($this->lundi->copy()->setTime(8, 45));

        $creneaux = $this->service->creneauxLibres($this->medecin, $this->lundi);

        $this->assertSame(['09:00', '09:30'], $this->heures($creneaux));
    }

    public function test_est_libre_verifie_un_creneau_precis()
    {
        $this->assertTrue($this->service->estLibre($this->medecin, Carbon::parse('2030-01-07 09:30')));
        $this->assertFalse($this->service->estLibre($this->medecin, Carbon::parse('2030-01-07 09:15')));
        $this->assertFalse($this->service->estLibre($this->medecin, Carbon::parse('2030-01-07 10:00')));
    }
}
