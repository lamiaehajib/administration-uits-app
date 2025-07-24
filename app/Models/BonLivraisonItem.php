<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonLivraisonItem extends Model
{
    use HasFactory;

    protected $table = 'bon_livraison_items';

    protected $fillable = [
        'libelle',
        'quantite',
        'prix_ht',
        'prix_total',
        'bon_livraison_id',
    ];

    protected $casts = [
        'prix_ht' => 'decimal:2',
        'prix_total' => 'decimal:2',
    ];

    public function bonLivraison()
    {
        return $this->belongsTo(BonLivraison::class);
    }
}