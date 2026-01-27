<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepenseFixe extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'depenses_fixes';

    protected $fillable = [
        'type',
        'libelle',
        'description',
        'montant_mensuel',
        'reference_contrat',
        'date_debut',
        'date_fin',
        'statut',
        'rappel_actif',
        'jour_paiement',
        'rappel_avant_jours',
        'fichier_contrat',
        'fichiers_justificatifs',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'montant_mensuel' => 'decimal:2',
        'fichiers_justificatifs' => 'array',
        'rappel_actif' => 'boolean',
    ];

    // ========================================
    // Relations
    // ========================================
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function rappels()
    {
        return $this->morphMany(RappelPaiement::class, 'source');
    }

    // ========================================
    // Scopes
    // ========================================
    
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif')
                    ->where(function($q) {
                        $q->whereNull('date_fin')
                          ->orWhere('date_fin', '>=', now());
                    });
    }

    public function scopePourMois($query, $annee, $mois)
    {
        $debut = \Carbon\Carbon::create($annee, $mois, 1)->startOfMonth();
        $fin = \Carbon\Carbon::create($annee, $mois, 1)->endOfMonth();

        return $query->where('statut', 'actif')
                    ->where('date_debut', '<=', $fin)
                    ->where(function($q) use ($debut) {
                        $q->whereNull('date_fin')
                          ->orWhere('date_fin', '>=', $debut);
                    });
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    // ========================================
    // Accessors
    // ========================================
    
    public function getTypeLibelleAttribute()
    {
        $types = [
            'salaire' => 'Salaires',
            'loyer' => 'Loyer',
            'internet' => 'Internet',
            'mobile' => 'Mobile',
            'srmc' => 'SRMC',
            'femme_menage' => 'Femme de ménage',
            'frais_aups' => 'Frais AUPS',
            'autre' => 'Autre'
        ];

        return $types[$this->type] ?? $this->type;
    }

    public function getStatutBadgeAttribute()
    {
        $badges = [
            'actif' => '<span class="badge bg-success">Actif</span>',
            'inactif' => '<span class="badge bg-secondary">Inactif</span>',
            'suspendu' => '<span class="badge bg-warning">Suspendu</span>',
        ];

        return $badges[$this->statut] ?? '';
    }

    public function getLibelleCompletAttribute()
    {
        if ($this->type === 'autre' && $this->libelle) {
            return $this->libelle;
        }
        
        return $this->type_libelle;
    }

    // ========================================
    // Méthodes utiles
    // ========================================
    
    /**
     * Vérifier si la dépense est active pour une date donnée
     */
    public function isActivePour($date)
    {
        $date = \Carbon\Carbon::parse($date);
        
        return $this->statut === 'actif' 
            && $this->date_debut <= $date
            && ($this->date_fin === null || $this->date_fin >= $date);
    }

    /**
     * Calculer le montant total sur une période
     */
    public function montantPourPeriode($dateDebut, $dateFin)
    {
        $debut = \Carbon\Carbon::parse($dateDebut)->startOfMonth();
        $fin = \Carbon\Carbon::parse($dateFin)->endOfMonth();
        
        $mois = $debut->diffInMonths($fin) + 1;
        
        return $this->montant_mensuel * $mois;
    }
}