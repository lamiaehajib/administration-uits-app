<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attestation extends Model
{
    use HasFactory;

    protected $fillable = [
        'stagiaire_name',
        'stagiaire_cin',
        'date_debut',
        'date_fin',
        'poste',
        'user_id',
        'afficher_cachet',
    ];

    public function Dashboard()
    {
        return $this->hasMany(Dashboard::class, 'attestation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
