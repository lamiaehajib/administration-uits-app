<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'specialite',
        'adresse',
        'cin',
        'tarif_heure',
        'actif',
    ];

    protected $casts = [
        'tarif_heure' => 'decimal:2',
        'actif' => 'boolean',
    ];

    /**
     * Factures reçues de ce consultant (Polymorphic)
     */
    public function facturesRecues()
    {
        return $this->morphMany(FactureRecue::class, 'fournisseur');
    }

    /**
     * Nom complet du consultant
     */
    public function getNomCompletAttribute()
    {
        return trim($this->nom . ' ' . $this->prenom);
    }

    /**
     * Scope pour les consultants actifs
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}