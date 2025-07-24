<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attestationallinone extends Model
{
    use HasFactory;

    protected $table = 'attestations_allinone';
    protected $fillable = [
        
        'personne_name',
        'cin',
        'numero_de_serie',
     'user_id',
     'afficher_cachet',
       
    ];

    public function Dashboard()
    {
        return $this->hasMany(Dashboard::class, 'attestation_allinone_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
