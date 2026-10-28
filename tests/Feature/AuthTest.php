<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_mot_de_passe_du_compte_de_demonstration_respecte_la_regle_d_inscription()
    {
        $this->post('/inscription', [
            'name' => 'Test',
            'email' => 'regle@test.com',
            'password' => UserSeeder::MOT_DE_PASSE,
            'password_confirmation' => UserSeeder::MOT_DE_PASSE,
        ])->assertSessionHasNoErrors();
    }

    public function test_le_compte_de_demonstration_est_remis_en_etat_sans_doublon()
    {
        $this->seed(UserSeeder::class);
        User::where('email', UserSeeder::EMAIL)->update(['password' => bcrypt('autre')]);

        $this->seed(UserSeeder::class);

        $this->assertSame(1, User::where('email', UserSeeder::EMAIL)->count());

        $this->post('/connexion', ['email' => UserSeeder::EMAIL, 'password' => UserSeeder::MOT_DE_PASSE])
            ->assertRedirect('/rendez-vous');
        $this->assertAuthenticated();
    }

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
            'password' => 'MotDePasse1!',
            'password_confirmation' => 'MotDePasse1!',
        ])->assertRedirect('/rendez-vous');

        $this->assertDatabaseHas('users', ['email' => 'nouveau@test.com']);
        $this->assertAuthenticated();
    }

    public function test_inscription_refusee_si_mot_de_passe_trop_faible()
    {
        $this->post('/inscription', [
            'name' => 'Test',
            'email' => 'faible@test.com',
            'password' => 'motdepasse',
            'password_confirmation' => 'motdepasse',
        ])->assertSessionHasErrors('password');

        $this->assertSame([
            'Le champ mot de passe doit contenir au moins une majuscule et une minuscule.',
            'Le champ mot de passe doit contenir au moins un caractère spécial.',
            'Le champ mot de passe doit contenir au moins un chiffre.',
        ], session('errors')->get('password'));

        // Les trois messages sont affichés en une fois sur le formulaire
        $this->get('/inscription')
            ->assertSee('au moins une majuscule et une minuscule')
            ->assertSee('au moins un caractère spécial')
            ->assertSee('au moins un chiffre.');

        $this->assertDatabaseMissing('users', ['email' => 'faible@test.com']);
        $this->assertGuest();
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
