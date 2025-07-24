<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'nom',
        'category_id',
        'prix_vendu',
        'quantite_stock',
        'total_vendu'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }

    public function ventes()
    {
        return $this->hasMany(Vente::class);
    }

    public function marge()
    {
        return $this->hasOne(Marge::class);
    }

    // حساب `marge` تلقائيًا عند حفظ المنتج
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($produit) {
            $achat = $produit->achats()->latest()->first();
            $vente = $produit->ventes()->latest()->first();

            if ($achat && $vente) {
                $marge = $vente->prix_vendu - $achat->prix_achat;
                Marge::updateOrCreate(
                    ['produit_id' => $produit->id],
                    ['marge' => $marge]
                );
            }
        });
    }
}
