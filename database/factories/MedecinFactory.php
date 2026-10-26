<?php

namespace Database\Factories;

use App\Models\Centre;
use App\Models\Specialite;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedecinFactory extends Factory
{
    public function definition()
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'specialite_id' => Specialite::factory(),
            'centre_id' => Centre::factory(),
        ];
    }

    /**
     * Ajoute une plage de consultation (jour ISO : 1 = lundi).
     */
    public function disponible($jourSemaine, $heureDebut, $heureFin)
    {
        return $this->afterCreating(function ($medecin) use ($jourSemaine, $heureDebut, $heureFin) {
            $medecin->disponibilites()->create([
                'jour_semaine' => $jourSemaine,
                'heure_debut' => $heureDebut,
                'heure_fin' => $heureFin,
            ]);
        });
    }
}
