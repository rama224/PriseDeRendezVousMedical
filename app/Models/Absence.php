<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    public const MOTIFS = [
        'maladie' => 'Maladie',
        'conges' => 'Congés',
        'autre' => 'Autre',
    ];

    protected $fillable = ['medecin_id', 'date_debut', 'date_fin', 'motif'];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function getMotifLibelleAttribute()
    {
        return self::MOTIFS[$this->motif] ?? $this->motif;
    }
}
