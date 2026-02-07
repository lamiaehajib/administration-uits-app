<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class RecuUcg extends Model
{
    use HasFactory, SoftDeletes;

    // Constantes
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_LIVRE = 'livre';
    const STATUT_ANNULE = 'annule';
    const STATUT_RETOUR = 'retour';

    const STATUT_PAIEMENT_PAYE = 'paye';
    const STATUT_PAIEMENT_PARTIEL = 'partiel';
    const STATUT_PAIEMENT_IMPAYE = 'impaye';

    const STATUT_GARANTIE_30_JOURS = '30_jours';
    const STATUT_GARANTIE_90_JOURS = '90_jours';
    const STATUT_GARANTIE_180_JOURS = '180_jours';
    const STATUT_GARANTIE_360_JOURS = '360_jours';
    const STATUT_GARANTIE_SANS = 'sans_garantie';
 protected $table = 'recus_ucgs';
    protected $fillable = [
        'numero_recu',
        'user_id',
        'client_nom',
        'client_prenom',
        'client_telephone',
        'client_email',
        'client_adresse',
        'equipement',
        'details',
        'type_garantie',
        'date_garantie_fin',
        'sous_total',
        'remise',
        'tva',
        'total',
        'montant_paye',
        'reste',
        'statut',
        'statut_paiement',
        'mode_paiement',
        'date_paiement',
        'notes',
    ];

    protected $casts = [
        'sous_total' => 'decimal:2',
        'remise' => 'decimal:2',
        'tva' => 'decimal:2',
        'total' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'reste' => 'decimal:2',
        'date_paiement' => 'datetime',
        'date_garantie_fin' => 'datetime',
    ];

    // ================================= RELATIONS ==============================
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function items()
    {
        return $this->hasMany(\App\Models\RecuItem::class);
    }

    public function paiements()
    {
        return $this->hasMany(\App\Models\Paiement::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(\App\Models\StockMovement::class);
    }

    // ================================= BOOT EVENTS ==============================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recu) {
            if (empty($recu->numero_recu)) {
                $recu->numero_recu = self::generateNumeroRecu();
            }
            
            if ($recu->type_garantie !== self::STATUT_GARANTIE_SANS) {
                $jours = match($recu->type_garantie) {
                    self::STATUT_GARANTIE_30_JOURS => 30,
                    self::STATUT_GARANTIE_90_JOURS => 90,
                    self::STATUT_GARANTIE_180_JOURS => 180,
                    self::STATUT_GARANTIE_360_JOURS => 360,
                    default => 90
                };
                $recu->date_garantie_fin = Carbon::now()->addDays($jours);
            }
            
            $recu->statut_paiement = self::STATUT_PAIEMENT_IMPAYE;
        });

        static::updating(function ($recu) {
            if ($recu->isDirty('montant_paye') || $recu->isDirty('total')) {
                $recu->reste = $recu->total - $recu->montant_paye;

                // Tolérance 1 centime pour les arrondis
                if ($recu->montant_paye >= $recu->total - 0.01) {
                    $recu->statut_paiement = self::STATUT_PAIEMENT_PAYE;
                    $recu->reste = 0.00;
                } elseif ($recu->montant_paye > 0) {
                    $recu->statut_paiement = self::STATUT_PAIEMENT_PARTIEL;
                } else {
                    $recu->statut_paiement = self::STATUT_PAIEMENT_IMPAYE;
                    $recu->reste = $recu->total;
                }
            }
        });

        static::deleting(function ($recu) {
            if (!$recu->isForceDeleting()) {
                $recu->load('items.produit');
                
                foreach ($recu->items->whereNotNull('produit') as $item) { 
                    $item->produit->increment('quantite_stock', $item->quantite);
                    
                    \App\Models\StockMovement::create([
                        'produit_id' => $item->produit_id,
                        'recu_ucg_id' => $recu->id,
                        'user_id' => auth()->id(),
                        'type' => 'retour',
                        'quantite' => $item->quantite,
                        'stock_avant' => $item->produit->quantite_stock - $item->quantite, 
                        'stock_apres' => $item->produit->quantite_stock,
                        'motif' => "Annulation reçu {$recu->numero_recu}"
                    ]);
                }
            }
        });
    }

    // ================================= MUTATEURS ==============================
    public function setClientNomAttribute($value)
    {
        $this->attributes['client_nom'] = ucwords(strtolower($value));
    }

    public function setClientPrenomAttribute($value)
    {
        $this->attributes['client_prenom'] = ucwords(strtolower($value));
    }

    // ================================= ACCESSEURS ==============================
    public function getGarantieStatusAttribute()
    {
        if ($this->type_garantie === self::STATUT_GARANTIE_SANS || !$this->date_garantie_fin) {
            return 'Non applicable';
        }

        if (Carbon::now()->isBefore($this->date_garantie_fin)) {
            return 'Valide';
        }

        return 'Expirée';
    }

    /**
     * ✅ MARGE GLOBALE - Somme des marges APRÈS remises
     */
    public function getTotalMargeAttribute()
    {
        if (!$this->relationLoaded('items')) {
            $this->load('items'); 
        }
        
        // Retourne la somme des marges (déjà ajustées par remises dans RecuItem)
        return $this->items->sum('marge_totale'); 
    }

    // ================================= MÉTHODES ==============================
    
    public static function generateNumeroRecu()
    {
        $year = date('Y');
        $lastRecu = self::whereYear('created_at', $year)
            ->latest()
            ->lockForUpdate()
            ->first();

        $number = $lastRecu ? intval(substr($lastRecu->numero_recu, -4)) + 1 : 1;
        return sprintf('UCGS-%s-%04d', $year, $number);
    }

    /**
     * ✅ CALCUL TOTAL MODIFIÉ - Prend en compte les remises par article
     */
    public function calculerTotal()
    {
        if (!$this->relationLoaded('items')) {
            $this->load('items'); 
        }
        
        // 1️⃣ Calculer sous-total (somme des sous-totaux SANS remises)
        $this->sous_total = $this->items->sum('sous_total');
        
        // 2️⃣ Calculer total des remises appliquées sur les items
        $totalRemisesItems = $this->items->sum('montant_remise');
        
        // 3️⃣ Sauvegarder total remises dans le champ 'remise' (pour affichage)
        $this->remise = $totalRemisesItems;
        
        // 4️⃣ Calculer total = Sous-total - Remises + TVA
        $this->total = $this->sous_total - $totalRemisesItems + $this->tva;
        
        // 5️⃣ Calculer reste à payer
        $this->reste = $this->total - $this->montant_paye;
        
        // 6️⃣ Sauvegarder
        $this->saveQuietly();
    }
    
    /**
     * ✅ MARGE GLOBALE - Retourne la marge totale (déjà impactée par remises)
     */
    public function margeGlobale(): float
    {
        if (!$this->relationLoaded('items')) {
            $this->load('items'); 
        }
        
        // Les marges dans items sont déjà ajustées par les remises
        return (float) $this->items->sum('marge_totale');
    }

    /**
     * ✅ TAUX DE MARGE - Basé sur le TOTAL final
     */
    public function tauxMarge(): float
    {
        if ($this->total == 0) {
            return 0;
        }
        
        return ($this->margeGlobale() / $this->total) * 100;
    }

    /**
     * ✅ MARGE APRÈS REMISE - Alias pour compatibilité
     */
    public function margeApresRemise(): float
    {
        return $this->margeGlobale();
    }

    /**
     * ✅ TAUX DE MARGE RÉEL - Alias pour compatibilité
     */
    public function tauxMargeReel(): float
    {
        return $this->tauxMarge();
    }

    public function isGarantieValide(): bool
    {
        return $this->date_garantie_fin && Carbon::now()->lte($this->date_garantie_fin);
    }

    public function ajouterPaiement(float $montant, string $mode = 'especes', ?string $reference = null): \App\Models\Paiement
    {
        // Créer le paiement
        $paiement = $this->paiements()->create([
            'montant' => $montant,
            'mode_paiement' => $mode,
            'reference' => $reference,
            'date_paiement' => now(),
            'user_id' => auth()->id()
        ]);

        // Calculer nouveau montant payé
        $nouveauMontantPaye = $this->montant_paye + $montant;
        
        // Update directement
        $this->montant_paye = $nouveauMontantPaye;
        $this->reste = $this->total - $nouveauMontantPaye;
        
        // Déterminer statut (avec tolérance)
        if ($nouveauMontantPaye >= $this->total - 0.01) {
            $this->statut_paiement = self::STATUT_PAIEMENT_PAYE;
            $this->reste = 0.00;
        } elseif ($nouveauMontantPaye > 0) {
            $this->statut_paiement = self::STATUT_PAIEMENT_PARTIEL;
        } else {
            $this->statut_paiement = self::STATUT_PAIEMENT_IMPAYE;
        }
        
        // Sauvegarder
        $this->save();
        
        return $paiement;
    }

    // ================================= SCOPES ==============================
    public function scopeDuMois($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeImpaye($query)
    {
        return $query->where('statut_paiement', self::STATUT_PAIEMENT_IMPAYE);
    }
}