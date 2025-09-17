<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ucg extends Model {
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'nom', 'prenom', 'recu_garantie', 'details', 'montant_paye', 'date_paiement','equipemen'
    ];
}
