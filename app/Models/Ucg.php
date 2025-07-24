<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ucg extends Model {
    use HasFactory;

    protected $fillable = [
        'nom', 'prenom', 'recu_garantie', 'details', 'montant_paye', 'date_paiement','equipemen'
    ];
}
