<?php

namespace Tests\Feature;

use App\Models\Centre;
use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\Specialite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RendezVousTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    private $patient;

    /** @var Medecin */
    private $medecin;

    protected function setUp(): void
    {
        parent::setUp();

        // Lundi 7 janvier 2030, 7h : tous les créneaux du jour sont à venir.
        $this->travelTo(Carbon::parse('2030-01-07 07:00'));

        $this->patient = User::factory()->create();
        $this->medecin = Medecin::factory()->disponible(1, '08:00', '12:00')->create();
    }

    private function reserver($dateHeure, User $patient = null)
    {
        return $this->actingAs($patient ?: $this->patient)->post('/rendez-vous', [
            'medecin_id' => $this->medecin->id,
            'date_heure' => $dateHeure,
            'motif' => 'Consultation',
        ]);
    }

    public function test_seules_les_specialites_du_centre_choisi_sont_proposees()
    {
        $autreSpecialite = Medecin::factory()->create()->specialite;

        $this->actingAs($this->patient)
            ->get('/rendez-vous/nouveau?centre_id=' . $this->medecin->centre_id)
            ->assertOk()
            ->assertSee($this->medecin->specialite->nom)
            ->assertDontSee($autreSpecialite->nom);
    }

    public function test_les_medecins_et_leurs_creneaux_s_affichent()
    {
        $this->actingAs($this->patient)
            ->get(sprintf('/rendez-vous/nouveau?centre_id=%d&specialite_id=%d&date=2030-01-07', $this->medecin->centre_id, $this->medecin->specialite_id))
            ->assertOk()
            ->assertSee($this->medecin->nom_complet)
            ->assertSee('08:00')
            ->assertSee('11:30');
    }

    public function test_un_medecin_absent_est_signale_sans_creneaux()
    {
        $this->medecin->absences()->create(['date_debut' => '2030-01-07', 'date_fin' => '2030-01-07', 'motif' => 'maladie']);

        $this->actingAs($this->patient)
            ->get(sprintf('/rendez-vous/nouveau?centre_id=%d&specialite_id=%d&date=2030-01-07', $this->medecin->centre_id, $this->medecin->specialite_id))
            ->assertSee('Absent (Maladie)')
            ->assertDontSee('08:00');
    }

    public function test_le_menu_indique_la_page_active()
    {
        $this->actingAs($this->patient)
            ->get('/rendez-vous/nouveau')
            ->assertSee('class="nav-link active"', false)
            ->assertSee('aria-current="page"', false);
    }

    public function test_on_peut_quitter_la_confirmation_sans_reserver()
    {
        $this->actingAs($this->patient)
            ->get('/rendez-vous/confirmer?medecin_id=' . $this->medecin->id . '&date_heure=2030-01-07+09:00')
            ->assertOk()
            ->assertSee('Quitter sans réserver')
            ->assertSee('Choisir un autre créneau');

        $this->assertSame(0, RendezVous::count());
    }

    public function test_une_date_passee_est_refusee()
    {
        $this->actingAs($this->patient)
            ->get('/rendez-vous/nouveau?date=2030-01-06')
            ->assertSessionHasErrors('date');
    }

    public function test_reserver_un_creneau_libre()
    {
        $this->reserver('2030-01-07 09:00')
            ->assertRedirect('/rendez-vous')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('rendez_vous', [
            'user_id' => $this->patient->id,
            'medecin_id' => $this->medecin->id,
            'date_heure' => '2030-01-07 09:00:00',
            'statut' => RendezVous::STATUT_CONFIRME,
        ]);
    }

    public function test_un_creneau_deja_reserve_est_refuse()
    {
        $this->reserver('2030-01-07 09:00');

        $this->reserver('2030-01-07 09:00', User::factory()->create())
            ->assertSessionHasErrors('reservation');

        $this->assertSame(1, RendezVous::count());
    }

    public function test_un_creneau_pendant_une_absence_est_refuse()
    {
        $this->medecin->absences()->create(['date_debut' => '2030-01-07', 'date_fin' => '2030-01-09', 'motif' => 'conges']);

        $this->reserver('2030-01-07 09:00')->assertSessionHasErrors('reservation');

        $this->assertSame(0, RendezVous::count());
    }

    public function test_un_creneau_hors_des_horaires_est_refuse()
    {
        $this->reserver('2030-01-07 14:00')->assertSessionHasErrors('reservation');
        $this->reserver('2030-01-07 09:15')->assertSessionHasErrors('reservation');

        $this->assertSame(0, RendezVous::count());
    }

    public function test_un_seul_rendez_vous_a_venir_par_medecin()
    {
        $this->reserver('2030-01-07 09:00');

        $this->reserver('2030-01-07 10:00')
            ->assertSessionHasErrors(['reservation' => 'Vous avez déjà un rendez-vous à venir avec ' . $this->medecin->nom_complet . ' (lundi 7 janvier à 09:00). Annulez-le si vous souhaitez changer de date.']);

        $this->assertSame(1, RendezVous::count());
    }

    public function test_la_regle_est_signalee_des_la_page_de_confirmation()
    {
        $this->reserver('2030-01-07 09:00');

        $this->actingAs($this->patient)
            ->get('/rendez-vous/confirmer?medecin_id=' . $this->medecin->id . '&date_heure=2030-01-07+10:00')
            ->assertRedirect()
            ->assertSessionHasErrors('reservation');
    }

    public function test_apres_annulation_le_patient_peut_reprendre_rendez_vous_avec_le_meme_medecin()
    {
        $this->reserver('2030-01-07 09:00');
        RendezVous::first()->update(['statut' => RendezVous::STATUT_ANNULE]);

        $this->reserver('2030-01-07 10:00')->assertSessionHasNoErrors();

        $this->assertSame(2, RendezVous::count());
    }

    public function test_un_rendez_vous_passe_avec_le_medecin_ne_bloque_pas_une_nouvelle_reservation()
    {
        $this->patient->rendezVous()->create(['medecin_id' => $this->medecin->id, 'date_heure' => '2029-12-10 09:00:00']);

        $this->reserver('2030-01-07 09:00')->assertSessionHasNoErrors();

        $this->assertSame(2, RendezVous::count());
    }

    public function test_pas_deux_rendez_vous_a_la_meme_heure_avec_des_medecins_differents()
    {
        $autreMedecin = Medecin::factory()->disponible(1, '08:00', '12:00')->create();
        $this->reserver('2030-01-07 09:00');

        $this->actingAs($this->patient)
            ->post('/rendez-vous', ['medecin_id' => $autreMedecin->id, 'date_heure' => '2030-01-07 09:00'])
            ->assertSessionHasErrors(['reservation' => 'Vous avez déjà un rendez-vous le lundi 7 janvier à 09:00 avec ' . $this->medecin->nom_complet . '.']);

        $this->actingAs($this->patient)
            ->post('/rendez-vous', ['medecin_id' => $autreMedecin->id, 'date_heure' => '2030-01-07 10:00'])
            ->assertSessionHasNoErrors();

        $this->assertSame(2, RendezVous::count());
    }

    public function test_le_patient_voit_ses_rendez_vous()
    {
        $this->reserver('2030-01-07 10:30');

        $this->actingAs($this->patient)
            ->get('/rendez-vous')
            ->assertOk()
            ->assertSee($this->medecin->nom_complet)
            ->assertSee('10:30');
    }

    public function test_le_patient_peut_annuler_son_rendez_vous_et_le_creneau_se_libere()
    {
        $this->reserver('2030-01-07 09:00');
        $rendezVous = RendezVous::first();

        $this->actingAs($this->patient)
            ->patch("/rendez-vous/{$rendezVous->id}/annuler")
            ->assertSessionHas('success');

        $this->assertSame(RendezVous::STATUT_ANNULE, $rendezVous->fresh()->statut);

        $this->reserver('2030-01-07 09:00', User::factory()->create())
            ->assertSessionHasNoErrors();
    }

    public function test_un_patient_ne_peut_pas_annuler_le_rendez_vous_d_un_autre()
    {
        $this->reserver('2030-01-07 09:00');
        $rendezVous = RendezVous::first();

        $this->actingAs(User::factory()->create())
            ->patch("/rendez-vous/{$rendezVous->id}/annuler")
            ->assertForbidden();

        $this->assertSame(RendezVous::STATUT_CONFIRME, $rendezVous->fresh()->statut);
    }

    public function test_un_rendez_vous_passe_ne_peut_pas_etre_annule()
    {
        $this->reserver('2030-01-07 09:00');
        $rendezVous = RendezVous::first();

        $this->travelTo(Carbon::parse('2030-01-07 10:00'));

        $this->actingAs($this->patient)
            ->patch("/rendez-vous/{$rendezVous->id}/annuler")
            ->assertSessionHasErrors('rendez_vous');

        $this->assertSame(RendezVous::STATUT_CONFIRME, $rendezVous->fresh()->statut);
    }
}
