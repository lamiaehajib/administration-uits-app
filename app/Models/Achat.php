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
        'prix_vente_suggere', // ✅ NOUVEAU
        'marge_pourcentage',   // ✅ NOUVEAU
        'total_achat',
        'date_achat',
        'notes',
    ];

    protected $casts = [
        'date_achat' => 'date',
        'prix_achat' => 'decimal:2',
        'prix_vente_suggere' => 'decimal:2', // ✅ NOUVEAU
        'marge_pourcentage' => 'decimal:2',   // ✅ NOUVEAU
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
            
            // ✅ Calculer prix_vente_suggere si pas fourni
            if (empty($achat->prix_vente_suggere) && $achat->prix_achat > 0) {
                $margePct = $achat->marge_pourcentage ?? 20;
                $achat->prix_vente_suggere = $achat->prix_achat * (1 + ($margePct / 100));
            }
        });

        static::updating(function ($achat) {
            // ✅ Recalculer prix_vente si marge change
            if ($achat->isDirty('marge_pourcentage') && $achat->prix_achat > 0) {
                $achat->prix_vente_suggere = $achat->prix_achat * (1 + ($achat->marge_pourcentage / 100));
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