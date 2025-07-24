<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achat extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'quantite',
        'prix_achat',
        'total_achat'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
