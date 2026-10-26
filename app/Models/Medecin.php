<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'prenom', 'specialite_id', 'centre_id'];

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    public function centre()
    {
        return $this->belongsTo(Centre::class);
    }

    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class);
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    public function getNomCompletAttribute()
    {
        return "Dr {$this->prenom} {$this->nom}";
    }

    /**
     * Absence couvrant la date donnée, s'il y en a une.
     */
    public function absenceLe(Carbon $date)
    {
        return $this->absences()
            ->whereDate('date_debut', '<=', $date)
            ->whereDate('date_fin', '>=', $date)
            ->first();
    }
}
