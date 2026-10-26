<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'adresse', 'telephone', 'email'];

    public function medecins()
    {
        return $this->hasMany(Medecin::class);
    }
}
