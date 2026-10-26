<?php

namespace App\Http\Controllers;

use App\Exceptions\ReservationImpossible;
use App\Models\Centre;
use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\Specialite;
use App\Services\CreneauService;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    /** @var CreneauService */
    private $creneaux;

    /** @var ReservationService */
    private $reservations;

    public function __construct(CreneauService $creneaux, ReservationService $reservations)
    {
        $this->creneaux = $creneaux;
        $this->reservations = $reservations;
    }

    /**
     * Rendez-vous du patient connecté : à venir, puis historique.
     */
    public function index(Request $request)
    {
        $rendezVous = $request->user()->rendezVous()
            ->with(['medecin.centre', 'medecin.specialite'])
            ->orderBy('date_heure')
            ->get();

        $aVenir = $rendezVous->filter(function (RendezVous $rdv) {
            return $rdv->estAnnulable();
        });

        $historique = $rendezVous->diff($aVenir)->sortByDesc('date_heure');

        return view('rendez-vous.index', compact('aVenir', 'historique'));
    }

    /**
     * Parcours de réservation : centre → spécialité → date → médecins et créneaux.
     */
    public function create(Request $request)
    {
        $request->validate([
            'centre_id' => ['nullable', 'integer', 'exists:centres,id'],
            'specialite_id' => ['nullable', 'integer', 'exists:specialites,id'],
            'date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
        ], [
            'date.after_or_equal' => 'La date ne peut pas être dans le passé.',
        ]);

        $centres = Centre::orderBy('nom')->get();
        $centre = $centres->firstWhere('id', $request->input('centre_id'));
        $date = Carbon::parse($request->input('date', Carbon::today()->toDateString()));

        $specialites = collect();
        $specialite = null;
        $medecins = collect();

        if ($centre) {
            // Seules les spécialités exercées dans le centre choisi sont proposées.
            $specialites = Specialite::whereHas('medecins', function ($query) use ($centre) {
                $query->where('centre_id', $centre->id);
            })->orderBy('nom')->get();

            $specialite = $specialites->firstWhere('id', $request->input('specialite_id'));
        }

        if ($specialite) {
            $medecins = Medecin::where('centre_id', $centre->id)
                ->where('specialite_id', $specialite->id)
                ->orderBy('nom')
                ->get()
                ->map(function (Medecin $medecin) use ($date) {
                    $medecin->absence = $medecin->absenceLe($date);
                    $medecin->creneaux = $this->creneaux->creneauxLibres($medecin, $date);

                    return $medecin;
                });
        }

        return view('rendez-vous.create', compact('centres', 'centre', 'specialites', 'specialite', 'date', 'medecins'));
    }

    /**
     * Récapitulatif du créneau choisi avant validation.
     */
    public function confirmer(Request $request)
    {
        [$medecin, $dateHeure] = $this->creneauDemande($request);

        try {
            $this->reservations->verifier($request->user(), $medecin, $dateHeure);
        } catch (ReservationImpossible $e) {
            return $this->retourAuxCreneaux($medecin, $dateHeure, $e->getMessage());
        }

        $medecin->load(['centre', 'specialite']);

        return view('rendez-vous.confirmer', compact('medecin', 'dateHeure'));
    }

    public function store(Request $request)
    {
        [$medecin, $dateHeure] = $this->creneauDemande($request);
        $motif = $request->validate(['motif' => ['nullable', 'string', 'max:255']])['motif'] ?? null;

        try {
            $this->reservations->reserver($request->user(), $medecin, $dateHeure, $motif);
        } catch (ReservationImpossible $e) {
            return $this->retourAuxCreneaux($medecin, $dateHeure, $e->getMessage());
        }

        return redirect()->route('rendez-vous.index')->with('success', sprintf(
            'Votre rendez-vous avec %s le %s à %s est confirmé.',
            $medecin->nom_complet,
            $dateHeure->translatedFormat('l j F Y'),
            $dateHeure->format('H:i')
        ));
    }

    public function annuler(Request $request, RendezVous $rendezVous)
    {
        abort_unless($rendezVous->user_id === $request->user()->id, 403);

        if (!$rendezVous->estAnnulable()) {
            return back()->withErrors(['rendez_vous' => 'Ce rendez-vous ne peut plus être annulé.']);
        }

        $rendezVous->update(['statut' => RendezVous::STATUT_ANNULE]);

        return back()->with('success', 'Votre rendez-vous a été annulé.');
    }

    /**
     * Valide et retourne le médecin et le créneau transmis dans la requête.
     *
     * @return array{0: Medecin, 1: Carbon}
     */
    private function creneauDemande(Request $request)
    {
        $data = $request->validate([
            'medecin_id' => ['required', 'integer', 'exists:medecins,id'],
            'date_heure' => ['required', 'date_format:Y-m-d H:i'],
        ]);

        return [
            Medecin::findOrFail($data['medecin_id']),
            Carbon::createFromFormat('!Y-m-d H:i', $data['date_heure']),
        ];
    }

    private function retourAuxCreneaux(Medecin $medecin, Carbon $dateHeure, $erreur)
    {
        return redirect()
            ->route('rendez-vous.create', [
                'centre_id' => $medecin->centre_id,
                'specialite_id' => $medecin->specialite_id,
                'date' => $dateHeure->toDateString(),
            ])
            ->withErrors(['reservation' => $erreur]);
    }
}
