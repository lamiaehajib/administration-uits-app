<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FactureRecue extends Model
{
    use HasFactory, SoftDeletes;
protected $table = 'factures_recues';
    protected $fillable = [
        'numero_facture',
        'date_facture',
        'date_echeance',
        'fournisseur_type',
        'fournisseur_id',
       
        'montant_ttc',
        'description',
        'statut',
        'fichier_pdf',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_facture' => 'date',
        'date_echeance' => 'date',
       
        'montant_ttc' => 'decimal:2',
    ];

    /**
     * Relation polymorphique vers Consultant ou Fournisseur
     */
    public function fournisseur()
    {
        return $this->morphTo();
    }

    /**
     * User qui a créé la facture
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User qui a modifié la facture
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Boot method pour calculer automatiquement le montant TTC
     */
   

    /**
     * Scope pour filtrer par statut
     */
    public function scopeStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour les factures en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les factures payées
     */
    public function scopePayee($query)
    {
        return $query->where('statut', 'payee');
    }

    /**
     * Obtenir le nom du fournisseur (Consultant ou Fournisseur)
     */
    public function getNomFournisseurAttribute()
    {
        if ($this->fournisseur_type === 'App\Models\Consultant') {
            return $this->fournisseur->nom_complet ?? 'N/A';
        }
        
        return $this->fournisseur->nom_entreprise ?? 'N/A';
    }
}