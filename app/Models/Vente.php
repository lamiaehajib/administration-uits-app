<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'quantite_vendue',
        'prix_vendu',
        'total_vendu',
        'marge',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($vente) {
            $produit = $vente->produit;
    
            if ($produit) {
                // حساب المارج تلقائيًا بناءً على المنتج عند الإنشاء
                $vente->marge = ($vente->prix_vendu - $produit->achats()->latest()->first()->prix_achat) * $vente->quantite_vendue;
            }
        });

        static::updating(function ($vente) {
            $produit = $vente->produit;
    
            if ($produit) {
                // إعادة حساب المارج تلقائيًا عند التحديث
                $vente->marge = ($vente->prix_vendu - $produit->achats()->latest()->first()->prix_achat) * $vente->quantite_vendue;
            }
        });
    }
}
