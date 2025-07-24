<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reussite extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 
        'prenom', 
        'duree_stage', 
        'montant_paye', 
        'date_paiement', 
        'prochaine_paiement',
        'CIN',
        'tele',
        'gmail',
        'rest',
        'user_id',
    ];


    public function Dashboard()
    {
        return $this->hasMany(Dashboard::class, 'reussite_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}