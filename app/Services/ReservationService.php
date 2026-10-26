<?php

namespace App\Services;

use App\Exceptions\ReservationImpossible;
use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    /** @var CreneauService */
    private $creneaux;

    public function __construct(CreneauService $creneaux)
    {
        $this->creneaux = $creneaux;
    }

    /**
     * Vérifie qu'un patient peut réserver ce créneau.
     *
     * - un seul rendez-vous à venir par médecin ;
     * - pas deux rendez-vous à la même heure ;
     * - le créneau doit être libre (horaires, absences, réservations).
     *
     * @throws ReservationImpossible
     */
    public function verifier(User $patient, Medecin $medecin, Carbon $dateHeure)
    {
        $dejaAVenir = $patient->rendezVous()->confirmes()
            ->where('medecin_id', $medecin->id)
            ->where('date_heure', '>', Carbon::now())
            ->first();

        if ($dejaAVenir) {
            throw new ReservationImpossible(sprintf(
                'Vous avez déjà un rendez-vous à venir avec %s (%s à %s). Annulez-le si vous souhaitez changer de date.',
                $medecin->nom_complet,
                $dejaAVenir->date_heure->translatedFormat('l j F'),
                $dejaAVenir->date_heure->format('H:i')
            ));
        }

        $memeHeure = $patient->rendezVous()->confirmes()
            ->where('date_heure', $dateHeure)
            ->with('medecin')
            ->first();

        if ($memeHeure) {
            throw new ReservationImpossible(sprintf(
                'Vous avez déjà un rendez-vous le %s à %s avec %s.',
                $dateHeure->translatedFormat('l j F'),
                $dateHeure->format('H:i'),
                $memeHeure->medecin->nom_complet
            ));
        }

        if (!$this->creneaux->estLibre($medecin, $dateHeure)) {
            throw new ReservationImpossible("Ce créneau n'est plus disponible. Merci d'en choisir un autre.");
        }
    }

    /**
     * Crée le rendez-vous après vérification des règles.
     *
     * @throws ReservationImpossible
     */
    public function reserver(User $patient, Medecin $medecin, Carbon $dateHeure, $motif = null)
    {
        return DB::transaction(function () use ($patient, $medecin, $dateHeure, $motif) {
            // Verrous sur le médecin et le patient : deux réservations simultanées
            // sont traitées l'une après l'autre, la seconde voit donc la première.
            Medecin::whereKey($medecin->id)->lockForUpdate()->first();
            User::whereKey($patient->id)->lockForUpdate()->first();

            $this->verifier($patient, $medecin, $dateHeure);

            return $patient->rendezVous()->create([
                'medecin_id' => $medecin->id,
                'date_heure' => $dateHeure,
                'motif' => $motif,
                'statut' => RendezVous::STATUT_CONFIRME,
            ]);
        });
    }
}
