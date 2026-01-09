<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureItem extends Model
{
    use HasFactory;

    protected $table = 'factures_items';

    protected $fillable = [
        'factures_id',
        'produit_id', // ✅ NOUVEAU
        'libele',
        'quantite',
        'prix_ht',
        'prix_achat', // ✅ NOUVEAU
        'marge_unitaire', // ✅ NOUVEAU
        'marge_totale', // ✅ NOUVEAU
        'prix_total',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_ht' => 'decimal:2',
        'prix_achat' => 'decimal:2',
        'marge_unitaire' => 'decimal:2',
        'marge_totale' => 'decimal:2',
        'prix_total' => 'decimal:2',
    ];

    public function facture()
    {
        return $this->belongsTo(Facture::class, 'factures_id');
    }

    // ✅ NOUVEAU : Relation avec Produit
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}