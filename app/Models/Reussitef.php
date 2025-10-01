<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Reussitef extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'nom',
        'prenom',
        'formation',
        'montant_paye',
        'date_paiement',
        'prochaine_paiement',
        'CIN',
        'tele',
        'gmail',
        'rest',
        'user_id',
         'mode_paiement',
    ];

    public function Dashboard()
    {
        return $this->hasMany(Dashboard::class, 'reussitef_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
