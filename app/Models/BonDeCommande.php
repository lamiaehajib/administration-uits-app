<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonDeCommande extends Model
{

    use HasFactory;

    protected $table = 'bon_de_commande';
    protected $fillable = [
        'titre',
        'fichier_path',
        'date_commande',
    ];

    protected $casts = [
        'date_commande' => 'date', // Cast to Carbon date
    ];
}