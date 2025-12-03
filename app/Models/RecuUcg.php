<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class RecuUcg extends Model
{
    use HasFactory, SoftDeletes;
    
    // Constantes
    public const STATUT_GARANTIE_SANS = 'sans_garantie';
    public const STATUT_GARANTIE_90_JOURS = '90_jours';
    public const STATUT_GARANTIE_180_JOURS = '180_jours';
    public const STATUT_GARANTIE_360_JOURS = '360_jours';
    
    public const STATUT_PAIEMENT_IMPAYE = 'impaye';
    public const STATUT_PAIEMENT_PARTIEL = 'partiel';
    public const STATUT_PAIEMENT_PAYE = 'paye';

    protected $table = 'recus_ucgs';
    protected $fillable = [
        'numero_recu', 'user_id',
        'client_nom', 'client_prenom', 'client_telephone', 'client_email', 'client_adresse',
        'equipement', 'details', 'notes',
        'type_garantie', 'date_garantie_fin',
        'statut', 'statut_paiement',
        'sous_total', 'remise', 'tva', 'total', 'montant_paye', 'reste',
        'mode_paiement', 'date_paiement'
    ];

    protected $casts = [
        'date_garantie_fin' => 'date',
        'date_paiement' => 'date',
        'sous_total' => 'decimal:2',
        'remise' => 'decimal:2',
        'tva' => 'decimal:2',
        'total' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'reste' => 'decimal:2',
    ];
    
    protected $appends = ['garantie_status']; 

    // Relations
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

    // Boot Events
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recu) {
            if (empty($recu->numero_recu)) {
                $recu->numero_recu = self::generateNumeroRecu();
            }
            
            if ($recu->type_garantie !== self::STATUT_GARANTIE_SANS) {
                $jours = match($recu->type_garantie) {
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

    // Mutateurs
    public function setClientNomAttribute($value)
    {
        $this->attributes['client_nom'] = ucwords(strtolower($value));
    }

    public function setClientPrenomAttribute($value)
    {
        $this->attributes['client_prenom'] = ucwords(strtolower($value));
    }

    // Accesseur
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

    // Méthodes
    public static function generateNumeroRecu()
    {
        $year = date('Y');
        $lastRecu = self::whereYear('created_at', $year)
            ->latest()
            ->lockForUpdate()
            ->first();

        $number = $lastRecu ? intval(substr($lastRecu->numero_recu, -4)) + 1 : 1;
        return sprintf('UCG-%s-%04d', $year, $number);
    }

    /**
     * ✅ MÉTHODE CORRIGÉE - Calcule le total ET recalcule les marges avec remise
     */
    public function calculerTotal()
    {
        if (!$this->relationLoaded('items')) {
            $this->load('items'); 
        }
        
        // 1️⃣ Calculer sous-total (somme des items)
        $this->sous_total = $this->items->sum('sous_total');
        
        // 2️⃣ Calculer total avec remise et TVA
        $this->total = $this->sous_total - $this->remise + $this->tva;
        
        // 3️⃣ Sauvegarder le reçu
        $this->save();
        
        // 4️⃣ ✅ NOUVEAU : Recalculer les marges des items avec la remise proportionnelle
        $this->recalculerMargesAvecRemise();
    }
    
    /**
     * ✅ NOUVELLE MÉTHODE - Recalcule les marges en tenant compte de la remise
     * La remise est répartie proportionnellement sur chaque item
     */
    public function recalculerMargesAvecRemise()
    {
        if (!$this->relationLoaded('items')) {
            $this->load('items'); 
        }

        // Si pas de remise, pas besoin de recalculer
        if ($this->remise <= 0 || $this->sous_total <= 0) {
            return;
        }

        // Pourcentage de remise globale
        $tauxRemise = $this->remise / $this->sous_total;

        foreach ($this->items as $item) {
            // Remise proportionnelle pour cet item
            $remiseItem = $item->sous_total * $tauxRemise;
            
            // Prix de vente effectif après remise
            $prixVenteEffectif = ($item->sous_total - $remiseItem) / $item->quantite;
            
            // Nouvelle marge unitaire
            $margeUnitaireAvecRemise = $prixVenteEffectif - $item->prix_achat;
            
            // Nouvelle marge totale
            $margeTotaleAvecRemise = $margeUnitaireAvecRemise * $item->quantite;
            
            // ✅ Update l'item SANS déclencher les observers
            $item->updateQuietly([
                'marge_unitaire' => $margeUnitaireAvecRemise,
                'marge_totale' => $margeTotaleAvecRemise
            ]);
        }
    }
    
    /**
     * ✅ MÉTHODE CORRIGÉE - Retourne la marge globale avec remise
     */
    public function getTotalMargeAttribute()
    {
        if (!$this->relationLoaded('items')) {
            $this->load('items'); 
        }
        
        // Retourne la somme des marges recalculées avec remise
        return $this->items->sum('marge_totale'); 
    }
    
    public function margeGlobale(): float
    {
        return $this->getTotalMargeAttribute();
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

    // Scopes
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