<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonCommandeItem extends Model
{
    protected $table = 'bon_commande_item';

    protected $fillable = [
        'libelle',
        'quantite',
        'prix_ht',
        'prix_total',
        'bon_commande_r_id',
        
    ];

    protected $casts = [
        'prix_ht' => 'decimal:2',
        'prix_total' => 'decimal:2',
        'quantite' => 'integer',
    ];

    public function bonCommandeR()
    {
        return $this->belongsTo(BonCommandeR::class, 'bon_commande_r_id');
    }
}