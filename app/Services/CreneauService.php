<?php

namespace App\Services;

use App\Models\Medecin;
use App\Models\RendezVous;
use Carbon\Carbon;

class CreneauService
{
    /** Durée d'une consultation, en minutes. */
    public const DUREE_CRENEAU = 30;

    /**
     * Créneaux libres d'un médecin pour une date donnée.
     *
     * Un créneau est libre s'il tombe dans une plage de disponibilité du jour,
     * que le médecin n'est pas absent ce jour-là, qu'il n'est pas déjà réservé
     * et qu'il n'est pas déjà passé.
     *
     * @return Carbon[]
     */
    public function creneauxLibres(Medecin $medecin, Carbon $date)
    {
        $date = $date->copy()->startOfDay();

        if ($medecin->absenceLe($date)) {
            return [];
        }

        $reserves = RendezVous::confirmes()
            ->where('medecin_id', $medecin->id)
            ->whereDate('date_heure', $date)
            ->get()
            ->map(function (RendezVous $rendezVous) {
                return $rendezVous->date_heure->format('H:i');
            })
            ->all();

        $plages = $medecin->disponibilites()
            ->where('jour_semaine', $date->dayOfWeekIso)
            ->orderBy('heure_debut')
            ->get();

        $maintenant = Carbon::now();
        $creneaux = [];

        foreach ($plages as $plage) {
            $creneau = $date->copy()->setTimeFromTimeString($plage->heure_debut);
            $fin = $date->copy()->setTimeFromTimeString($plage->heure_fin);

            while ($creneau->copy()->addMinutes(self::DUREE_CRENEAU)->lte($fin)) {
                if ($creneau->gt($maintenant) && !in_array($creneau->format('H:i'), $reserves, true)) {
                    $creneaux[] = $creneau->copy();
                }

                $creneau->addMinutes(self::DUREE_CRENEAU);
            }
        }

        return $creneaux;
    }

    public function estLibre(Medecin $medecin, Carbon $dateHeure)
    {
        foreach ($this->creneauxLibres($medecin, $dateHeure) as $creneau) {
            if ($creneau->format('Y-m-d H:i') === $dateHeure->format('Y-m-d H:i')) {
                return true;
            }
        }

        return false;
    }
}
