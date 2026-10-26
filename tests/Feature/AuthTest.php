<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_visiteur_est_redirige_vers_la_connexion()
    {
        $this->get('/rendez-vous')->assertRedirect('/connexion');
    }

    public function test_la_page_de_connexion_s_affiche()
    {
        $this->get('/connexion')->assertOk()->assertSee('Connexion');
    }

    public function test_connexion_avec_des_identifiants_valides()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->post('/connexion', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/rendez-vous');

        $this->assertAuthenticatedAs($user);
    }

    public function test_connexion_refusee_avec_un_mauvais_mot_de_passe()
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->post('/connexion', ['email' => $user->email, 'password' => 'faux'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_inscription_cree_le_compte_et_connecte_l_utilisateur()
    {
        $this->post('/inscription', [
            'name' => 'Nouveau Patient',
            'email' => 'nouveau@test.com',
            'password' => 'motdepasse',
            'password_confirmation' => 'motdepasse',
        ])->assertRedirect('/rendez-vous');

        $this->assertDatabaseHas('users', ['email' => 'nouveau@test.com']);
        $this->assertAuthenticated();
    }

    public function test_inscription_refusee_si_email_deja_utilise_ou_mot_de_passe_trop_court()
    {
        User::factory()->create(['email' => 'pris@test.com']);

        $this->post('/inscription', [
            'name' => 'Test',
            'email' => 'pris@test.com',
            'password' => '123',
            'password_confirmation' => '123',
        ])->assertSessionHasErrors(['email', 'password']);

        $this->assertGuest();
    }

    public function test_deconnexion()
    {
        $this->actingAs(User::factory()->create())
            ->post('/deconnexion')
            ->assertRedirect('/connexion');

        $this->assertGuest();
    }
}
