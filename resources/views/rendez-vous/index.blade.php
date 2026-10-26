@extends('layouts.app')

@section('title', 'Mes rendez-vous')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <h1 class="h3 mb-0">Mes rendez-vous</h1>
    <a href="{{ route('rendez-vous.create') }}" class="btn btn-primary">Prendre un rendez-vous</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<h2 class="h5 mb-3">À venir</h2>

@forelse ($aVenir as $rdv)
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="fw-semibold">{{ ucfirst($rdv->date_heure->translatedFormat('l j F Y')) }} à {{ $rdv->date_heure->format('H:i') }}</div>
                <div>{{ $rdv->medecin->nom_complet }} · {{ $rdv->medecin->specialite->nom }}</div>
                <div class="text-muted small">{{ $rdv->medecin->centre->nom }}, {{ $rdv->medecin->centre->adresse }}</div>
                @if ($rdv->motif)
                    <div class="text-muted small">Motif : {{ $rdv->motif }}</div>
                @endif
            </div>

            <form method="POST" action="{{ route('rendez-vous.annuler', $rdv) }}"
                  onsubmit="return confirm('Voulez-vous vraiment annuler ce rendez-vous ?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-danger btn-sm">Annuler</button>
            </form>
        </div>
    </div>
@empty
    <p class="text-muted">Aucun rendez-vous à venir.</p>
@endforelse

@if ($historique->isNotEmpty())
    <h2 class="h5 mt-5 mb-3">Historique</h2>

    <div class="table-responsive">
        <table class="table table-sm align-middle bg-white">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Médecin</th>
                    <th>Centre</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($historique as $rdv)
                    <tr>
                        <td>{{ $rdv->date_heure->format('d/m/Y H:i') }}</td>
                        <td>{{ $rdv->medecin->nom_complet }}</td>
                        <td>{{ $rdv->medecin->centre->nom }}</td>
                        <td>
                            @if ($rdv->statut === \App\Models\RendezVous::STATUT_ANNULE)
                                <span class="badge bg-danger">Annulé</span>
                            @else
                                <span class="badge bg-secondary">Passé</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
