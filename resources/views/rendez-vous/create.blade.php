@extends('layouts.app')

@section('title', 'Prendre rendez-vous')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <h1 class="h3 mb-0">Prendre rendez-vous</h1>
    <a href="{{ route('rendez-vous.index') }}" class="btn btn-outline-secondary">&larr; Retour à mes rendez-vous</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('rendez-vous.create') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="centre_id" class="form-label">1. Centre médical</label>
                <select id="centre_id" name="centre_id" class="form-select" onchange="this.form.specialite_id.value = ''; this.form.submit()">
                    <option value="">Choisir un centre…</option>
                    @foreach ($centres as $c)
                        <option value="{{ $c->id }}" @if ($centre && $centre->id === $c->id) selected @endif>{{ $c->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="specialite_id" class="form-label">2. Spécialité</label>
                <select id="specialite_id" name="specialite_id" class="form-select" onchange="this.form.submit()" @unless ($centre) disabled @endunless>
                    <option value="">{{ $centre ? 'Choisir une spécialité…' : "Choisissez d'abord un centre" }}</option>
                    @foreach ($specialites as $s)
                        <option value="{{ $s->id }}" @if ($specialite && $specialite->id === $s->id) selected @endif>{{ $s->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="date" class="form-label">3. Date</label>
                <input id="date" type="date" name="date" class="form-control" value="{{ $date->toDateString() }}"
                       min="{{ now()->toDateString() }}" onchange="this.form.submit()">
            </div>

            <div class="col-md-1 d-grid">
                <button type="submit" class="btn btn-primary">OK</button>
            </div>
        </form>

        @if ($centre)
            <p class="text-muted small mt-3 mb-0">
                {{ $centre->nom }} · {{ $centre->adresse }} · {{ $centre->telephone }}
                @if ($centre->email) · {{ $centre->email }} @endif
            </p>
        @endif
    </div>
</div>

@if ($specialite)
    <h2 class="h5 mb-3">
        {{ $specialite->nom }} — {{ ucfirst($date->translatedFormat('l j F Y')) }}
    </h2>

    @forelse ($medecins as $medecin)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                    <h3 class="h6 mb-0">{{ $medecin->nom_complet }}</h3>

                    @if ($medecin->absence)
                        <span class="badge bg-secondary">
                            Absent ({{ $medecin->absence->motif_libelle }}) jusqu'au {{ $medecin->absence->date_fin->translatedFormat('j F') }}
                        </span>
                    @elseif (count($medecin->creneaux))
                        <span class="badge bg-success">{{ count($medecin->creneaux) }} créneau(x) libre(s)</span>
                    @endif
                </div>

                @if ($medecin->absence)
                    <p class="text-muted mb-0">Ce médecin n'est pas disponible à cette date.</p>
                @elseif (count($medecin->creneaux) === 0)
                    <p class="text-muted mb-0">Aucun créneau disponible ce jour-là. Essayez une autre date.</p>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($medecin->creneaux as $creneau)
                            <a class="btn btn-outline-primary btn-sm"
                               href="{{ route('rendez-vous.confirmer', ['medecin_id' => $medecin->id, 'date_heure' => $creneau->format('Y-m-d H:i')]) }}">
                                {{ $creneau->format('H:i') }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info">Aucun médecin de cette spécialité dans ce centre.</div>
    @endforelse
@endif
@endsection
