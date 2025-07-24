<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttestationFormation extends Model
{
    use HasFactory;
    protected $table = 'attestations_formation';
    protected $fillable = [
        'formation_name',
        'personne_name',
        'cin',
        'numero_de_serie',
        'user_id',
        'afficher_cachet',
       
    ];

    public function Dashboard()
    {
        return $this->hasMany(Dashboard::class, 'attestation_formation_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
