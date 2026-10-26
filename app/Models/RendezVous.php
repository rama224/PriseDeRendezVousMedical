<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;

    public const STATUT_CONFIRME = 'confirme';
    public const STATUT_ANNULE = 'annule';

    protected $table = 'rendez_vous';

    protected $fillable = ['user_id', 'medecin_id', 'date_heure', 'motif', 'statut'];

    protected $casts = [
        'user_id' => 'integer',
        'medecin_id' => 'integer',
        'date_heure' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function scopeConfirmes($query)
    {
        return $query->where('statut', self::STATUT_CONFIRME);
    }

    public function estAnnulable()
    {
        return $this->statut === self::STATUT_CONFIRME && $this->date_heure->isFuture();
    }
}
