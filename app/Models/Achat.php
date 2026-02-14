<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'produit_id',
        'user_id',
        'fournisseur',
        'numero_bon',
        'quantite',
        'quantite_restante',
        'prix_achat',
        'prix_vente_suggere',
        'marge_pourcentage',
        'total_achat',
        'date_achat',
        'notes',
    ];

    protected $casts = [
        'date_achat' => 'date',
        'prix_achat' => 'decimal:2',
        'prix_vente_suggere' => 'decimal:2',
        'marge_pourcentage' => 'decimal:2',
        'total_achat' => 'decimal:2',
        'quantite' => 'integer',
        'quantite_restante' => 'integer',
    ];

    // ✅ Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($achat) {
            // Quantite_restante = quantite
            $achat->quantite_restante = $achat->quantite;
            
            // ✅ NOUVEAU: Calculer marge_pourcentage si prix_vente_suggere est fourni
            if (!empty($achat->prix_vente_suggere) && $achat->prix_achat > 0) {
                $achat->marge_pourcentage = (($achat->prix_vente_suggere - $achat->prix_achat) / $achat->prix_achat) * 100;
            }
        });

        static::updating(function ($achat) {
            // ✅ NOUVEAU: Recalculer marge si prix de vente ou prix d'achat change
            if (($achat->isDirty('prix_vente_suggere') || $achat->isDirty('prix_achat')) && $achat->prix_achat > 0) {
                $achat->marge_pourcentage = (($achat->prix_vente_suggere - $achat->prix_achat) / $achat->prix_achat) * 100;
            }
        });
    }

    // Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Scopes
    public function scopeAvecStock($query)
    {
        return $query->where('quantite_restante', '>', 0);
    }

    public function scopeParDate($query)
    {
        return $query->orderBy('date_achat', 'asc');
    }

    // ✅ Accessors
    public function getQuantiteVendueAttribute()
    {
        return $this->quantite - $this->quantite_restante;
    }

    public function getEpuiseAttribute()
    {
        return $this->quantite_restante == 0;
    }

    public function getTauxUtilisationAttribute()
    {
        if ($this->quantite == 0) return 0;
        return round(($this->quantite_vendue / $this->quantite) * 100, 1);
    }

    // ✅ Calculer marge réelle
    public function getMargeUnitaireAttribute()
    {
        return $this->prix_vente_suggere - $this->prix_achat;
    }

    public function getMargePourcentageReelAttribute()
    {
        if ($this->prix_achat == 0) return 0;
        return round((($this->prix_vente_suggere - $this->prix_achat) / $this->prix_achat) * 100, 2);
    }
}