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

    // ========================================
    // 🔥 Boot - Auto-create DepenseVariable
    // ========================================
    
    protected static function booted()
    {
        // Quand une facture est créée, créer automatiquement une dépense variable
        static::created(function ($facture) {
            // Créer dépense variable automatiquement
            DepenseVariable::create([
                'type' => 'facture_recue',
                'libelle' => 'Facture ' . $facture->numero_facture . ' - ' . $facture->nom_fournisseur,
                'description' => $facture->description ?? 'Dépense générée automatiquement depuis facture reçue',
                'montant' => $facture->montant_ttc,
                'date_depense' => $facture->date_facture,
                'annee' => $facture->date_facture->year,
                'mois' => $facture->date_facture->month,
                'facture_recue_id' => $facture->id,
                'statut' => 'en_attente', // Par défaut en attente de validation
                'created_by' => $facture->created_by,
            ]);
        });
    }

    // ========================================
    // Relations
    // ========================================
    
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
     * Dépense variable associée à cette facture
     */
    public function depenseVariable()
    {
        return $this->hasOne(DepenseVariable::class, 'facture_recue_id');
    }

    // ========================================
    // Scopes
    // ========================================
    
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

    // ========================================
    // Accessors
    // ========================================
    
    /**
     * Obtenir le nom du fournisseur (Consultant ou Fournisseur)
     */
    public function getNomFournisseurAttribute()
    {
        if (!$this->fournisseur) {
            return 'N/A';
        }

        if ($this->fournisseur_type === 'App\Models\Consultant') {
            return $this->fournisseur->nom_complet ?? 'N/A';
        }
        
        return $this->fournisseur->nom_entreprise ?? 'N/A';
    }

    /**
     * Badge de statut HTML
     */
    public function getStatutBadgeAttribute()
    {
        $badges = [
            'en_attente' => '<span class="badge bg-warning">En attente</span>',
            'payee' => '<span class="badge bg-success">Payée</span>',
            'annulee' => '<span class="badge bg-danger">Annulée</span>',
        ];

        return $badges[$this->statut] ?? '';
    }
}