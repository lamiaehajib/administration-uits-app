<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fournisseur extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom_entreprise',
        'contact_nom',
        'email',
        'telephone',
        'ice',
        'if',
        'adresse',
        'type_materiel',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Factures reçues de ce fournisseur (Polymorphic)
     */
    public function facturesRecues()
    {
        return $this->morphMany(FactureRecue::class, 'fournisseur');
    }

    /**
     * Scope pour les fournisseurs actifs
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}