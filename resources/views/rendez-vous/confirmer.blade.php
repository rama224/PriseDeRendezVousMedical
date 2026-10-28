@extends('layouts.app')

@section('title', 'Confirmer le rendez-vous')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h1 class="h4 mb-4">Confirmer le rendez-vous</h1>

                <dl class="row mb-4">
                    <dt class="col-sm-4">Patient</dt>
                    <dd class="col-sm-8">{{ Auth::user()->name }}<br><small class="text-muted">{{ Auth::user()->email }}</small></dd>

                    <dt class="col-sm-4">Médecin</dt>
                    <dd class="col-sm-8">{{ $medecin->nom_complet }}</dd>

                    <dt class="col-sm-4">Spécialité</dt>
                    <dd class="col-sm-8">{{ $medecin->specialite->nom }}</dd>

                    <dt class="col-sm-4">Centre</dt>
                    <dd class="col-sm-8">{{ $medecin->centre->nom }}<br><small class="text-muted">{{ $medecin->centre->adresse }}</small></dd>

                    <dt class="col-sm-4">Date</dt>
                    <dd class="col-sm-8">{{ ucfirst($dateHeure->translatedFormat('l j F Y')) }} à {{ $dateHeure->format('H:i') }}</dd>
                </dl>

                <form method="POST" action="{{ route('rendez-vous.store') }}">
                    @csrf
                    <input type="hidden" name="medecin_id" value="{{ $medecin->id }}">
                    <input type="hidden" name="date_heure" value="{{ $dateHeure->format('Y-m-d H:i') }}">

                    <div class="mb-3">
                        <label for="motif" class="form-label">Motif de la consultation <span class="text-muted">(facultatif)</span></label>
                        <input id="motif" type="text" name="motif" maxlength="255" value="{{ old('motif') }}"
                               class="form-control @error('motif') is-invalid @enderror">
                        @error('motif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-2">Confirmer le rendez-vous</button>

                    <div class="d-flex gap-2">
                        <a href="{{ route('rendez-vous.create', ['centre_id' => $medecin->centre_id, 'specialite_id' => $medecin->specialite_id, 'date' => $dateHeure->toDateString()]) }}"
                           class="btn btn-outline-secondary flex-fill">&larr; Choisir un autre créneau</a>
                        <a href="{{ route('rendez-vous.index') }}" class="btn btn-outline-danger flex-fill">Quitter sans réserver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
