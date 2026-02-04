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
        'quantite_restante', // ✅ NOUVEAU
        'prix_achat',
        'total_achat',
        'date_achat',
        'notes',
    ];

    protected $casts = [
        'date_achat' => 'date',
        'prix_achat' => 'decimal:2',
        'total_achat' => 'decimal:2',
        'quantite' => 'integer',
        'quantite_restante' => 'integer', // ✅ NOUVEAU
    ];

    // ✅ Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($achat) {
            // F wa9t création, quantite_restante = quantite
            $achat->quantite_restante = $achat->quantite;
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
}