<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepenseVariable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'depenses_variables';

    protected $fillable = [
        'type',
        'libelle',
        'description',
        'montant',
        'date_depense',
        'annee',
        'mois',
        
        // Facture reçue
        'facture_recue_id',
        
        // Prime
        'user_mgmt_id',
        'nom_employe',
        'poste_employe',
        'montant_salaire',
        'type_prime',
        'motif_prime',
        
        // CNSS
        'montant_salaire_base',
        'taux_cnss',
        'repartition_cnss',
        
        // Publication
        'plateforme',
        'campagne',
        'date_debut_campagne',
        'date_fin_campagne',
        
        // Transport
        'type_transport',
        'beneficiaire',
        'trajet',
        'distance_km',
        
        // Statut
        'statut',
        'validee_par',
        'validee_le',
        
        'fichiers_justificatifs',
        'notes_internes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_depense' => 'date',
        'date_debut_campagne' => 'date',
        'date_fin_campagne' => 'date',
        'montant' => 'decimal:2',
        'montant_salaire' => 'decimal:2',
        'montant_salaire_base' => 'decimal:2',
        'taux_cnss' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'fichiers_justificatifs' => 'array',
        'repartition_cnss' => 'array',
        'validee_le' => 'datetime',
    ];

    // ========================================
    // Relations
    // ========================================
    
    public function factureRecue()
    {
        return $this->belongsTo(FactureRecue::class, 'facture_recue_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function valideePar()
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    public function rappels()
    {
        return $this->morphMany(RappelPaiement::class, 'source');
    }

    // ========================================
    // Scopes
    // ========================================
    
    public function scopePourMois($query, $annee, $mois)
    {
        return $query->where('annee', $annee)->where('mois', $mois);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeValidee($query)
    {
        return $query->whereIn('statut', ['validee', 'payee']);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    // ========================================
    // Accessors
    // ========================================
    
    public function getTypeLibelleAttribute()
    {
        $types = [
            'facture_recue' => 'Facture reçue',
            'prime' => 'Prime',
            'cnss' => 'CNSS',
            'publication' => 'Publication',
            'transport' => 'Transport',
            'dgi' => 'DGI',
            'comptabilite' => 'Comptabilité',
            'autre' => 'Autre'
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getStatutBadgeAttribute()
    {
        $badges = [
            'en_attente' => '<span class="badge bg-warning">En attente</span>',
            'validee' => '<span class="badge bg-info">Validée</span>',
            'payee' => '<span class="badge bg-success">Payée</span>',
            'annulee' => '<span class="badge bg-danger">Annulée</span>',
        ];

        return $badges[$this->statut] ?? '';
    }

    public function getMoisNomAttribute()
    {
        $mois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        return $mois[$this->mois] ?? '';
    }
}