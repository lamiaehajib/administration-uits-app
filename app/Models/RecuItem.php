<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class RecuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'recu_ucg_id', 
        'produit_id', 
        'product_variant_id',
        'produit_nom', 
        'produit_reference',
        'designation',
        'quantite', 
        'prix_unitaire', 
        'prix_achat',
        'sous_total', 
        'marge_unitaire', 
        'marge_totale',
        'remise_appliquee',
        'remise_montant',          // ✅ Nouveau: Montant fixe de remise
        'remise_pourcentage',      // ✅ Nouveau: Pourcentage de remise
        'total_apres_remise',      // ✅ Nouveau: Total après remise
        'notes',
        'achat_id',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'prix_achat' => 'decimal:2',
        'sous_total' => 'decimal:2',
        'marge_unitaire' => 'decimal:2',
        'marge_totale' => 'decimal:2',
        'remise_appliquee' => 'boolean',
        'remise_montant' => 'decimal:2',         // ✅ Nouveau
        'remise_pourcentage' => 'decimal:2',     // ✅ Nouveau
        'total_apres_remise' => 'decimal:2',     // ✅ Nouveau
        'achat_id' => 'integer',
    ];

    // ================================= RELATIONS ==============================
    
    public function achat()
{
    return $this->belongsTo(\App\Models\Achat::class);
}
    public function recuUcg()
    {
        return $this->belongsTo(RecuUcg::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // ================================= BOOT EVENTS ==============================
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
    if ($item->product_variant_id) {
        // === VARIANT ===
        $variant = ProductVariant::find($item->product_variant_id);
        if ($variant) {
            $item->produit_id         = $variant->produit_id;
            $item->produit_nom        = $variant->produit->nom;
            $item->produit_reference  = $variant->produit->reference;
            $item->designation        = $variant->variant_name;

            if (empty($item->prix_unitaire)) {
                $item->prix_unitaire = $variant->prix_vente_final;
            }
            if (empty($item->prix_achat)) {
                $achatActif = Achat::where('produit_id', $variant->produit_id)
                    ->where('quantite_restante', '>', 0)
                    ->orderBy('date_achat', 'asc')
                    ->first();
                $item->prix_achat = $achatActif ? $achatActif->prix_achat : ($variant->prix_achat ?? 0);
            }
        }
    } else {
        // === PRODUIT SIMPLE ===
        $produit = $item->produit;
        if ($produit) {
            $item->produit_nom       = $produit->nom;
            $item->produit_reference = $produit->reference;

            // ✅ CAS 1: Lot spécifique choisi (achat_id fourni)
            if (!empty($item->achat_id) && $item->achat_id !== 'manuel') {
                $achat = Achat::find($item->achat_id);
                if ($achat) {
                    $item->prix_achat    = $achat->prix_achat;
                    $item->prix_unitaire = $achat->prix_vente_suggere ?? $produit->prix_vente;
                    Log::info("✅ LOT CHOISI: Achat #{$achat->id} - PA: {$item->prix_achat} DH, PV: {$item->prix_unitaire} DH");
                }
            }
            // ✅ CAS 2: Stock manuel choisi
            elseif ($item->achat_id === 'manuel') {
                $item->prix_achat    = $produit->prix_achat ?? 0;
                $item->prix_unitaire = $produit->prix_vente ?? 0;
                $item->achat_id      = null; // reset - machi foreign key valide
                Log::info("✅ STOCK MANUEL CHOISI: PA: {$item->prix_achat} DH, PV: {$item->prix_unitaire} DH");
            }
            // ✅ CAS 3: Prix déjà fournis (depuis le form)
            elseif (!empty($item->prix_unitaire) && !empty($item->prix_achat)) {
                Log::info("✅ PRIX FOURNIS: PA: {$item->prix_achat} DH, PV: {$item->prix_unitaire} DH");
                // Ne rien changer, garder les prix fournis
            }
            // ✅ CAS 4: Fallback FIFO automatique
            else {
                $stockFifo   = Achat::where('produit_id', $produit->id)->where('quantite_restante', '>', 0)->sum('quantite_restante');
                $stockManuel = max(0, $produit->quantite_stock - $stockFifo);

                if ($stockManuel > 0) {
                    $item->prix_achat    = $produit->prix_achat ?? 0;
                    $item->prix_unitaire = $produit->prix_vente ?? 0;
                    Log::info("✅ FALLBACK MANUEL: PA: {$item->prix_achat} DH");
                } else {
                    $achatActif = Achat::where('produit_id', $produit->id)
                        ->where('quantite_restante', '>', 0)
                        ->orderBy('date_achat', 'asc')
                        ->first();
                    if ($achatActif) {
                        $item->prix_achat    = $achatActif->prix_achat;
                        $item->prix_unitaire = $achatActif->prix_vente_suggere ?? $produit->prix_vente;
                        $item->achat_id      = $achatActif->id;
                        Log::info("✅ FALLBACK FIFO: Achat #{$achatActif->id} - PA: {$item->prix_achat} DH");
                    } else {
                        $item->prix_achat    = $produit->prix_achat ?? 0;
                        $item->prix_unitaire = $produit->prix_vente ?? 0;
                        Log::warning("⚠️ FALLBACK DEFAULT: Aucun achat disponible");
                    }
                }
            }
        }
    }

    // Calculs
    $item->sous_total     = $item->quantite * $item->prix_unitaire;
    $item->marge_unitaire = $item->prix_unitaire - $item->prix_achat;
    $item->marge_totale   = $item->marge_unitaire * $item->quantite;

    if ($item->remise_appliquee) {
        $item->calculerRemise();
    } else {
        $item->total_apres_remise = $item->sous_total;
    }
});

   

        static::updating(function ($item) {
            // ✅ Recalculer si remise ou quantité change
            if ($item->isDirty(['remise_appliquee', 'remise_montant', 'remise_pourcentage', 'quantite', 'prix_unitaire'])) {
                // Recalculer sous-total et marges
                $item->sous_total = $item->quantite * $item->prix_unitaire;
                $item->marge_unitaire = $item->prix_unitaire - $item->prix_achat;
                $item->marge_totale = $item->marge_unitaire * $item->quantite;
                
                // Recalculer remise
                if ($item->remise_appliquee) {
                    $item->calculerRemise();
                } else {
                    $item->total_apres_remise = $item->sous_total;
                    $item->remise_montant = 0;
                    $item->remise_pourcentage = 0;
                }
            }
        });

        static::created(function ($item) {
    if ($item->product_variant_id) {
        $variant = $item->variant;
        
        if ($variant) {
            $stockAvant = $variant->quantite_stock;
            $variant->decrement('quantite_stock', $item->quantite);
            
            $produit = $variant->produit;
            $totalStock = $produit->variants()->sum('quantite_stock');
            $produit->update(['quantite_stock' => $totalStock]);

            StockMovement::create([
                'produit_id'  => $item->produit_id,
                'recu_ucg_id' => $item->recu_ucg_id,
                'user_id'     => auth()->id(),
                'type'        => 'sortie',
                'quantite'    => $item->quantite,
                'stock_avant' => $stockAvant,
                'stock_apres' => $variant->fresh()->quantite_stock,
                'motif'       => "Vente variant ({$variant->variant_name}) - Reçu #{$item->recuUcg->numero_recu}",
                'reference'   => "VARIANT-{$variant->id}"
            ]);
        }
    } else {
        // ✅ FIFO - Produit Simple
        $produit = $item->produit;

        if ($produit) {
            $stockAvant = $produit->quantite_stock;
            
            // ✅ بعث achat_id باش ينقص من الـ lot المختار بالضبط
            self::decrementerStockFIFO(
                $item->produit_id,
                $item->quantite,
                $item->recu_ucg_id,
                $item->achat_id  // ← الجديد
            );
            
            $produit->decrement('quantite_stock', $item->quantite);
            $produit->increment('total_vendu', $item->quantite);

            StockMovement::create([
                'produit_id'  => $produit->id,
                'recu_ucg_id' => $item->recu_ucg_id,
                'user_id'     => auth()->id(),
                'type'        => 'sortie',
                'quantite'    => $item->quantite,
                'stock_avant' => $stockAvant,
                'stock_apres' => $produit->fresh()->quantite_stock,
                'motif'       => $item->achat_id
                    ? "Vente Lot #{$item->achat_id} - Reçu #{$item->recuUcg->numero_recu}"
                    : "Vente FIFO - Reçu #{$item->recuUcg->numero_recu}"
            ]);
        }
    }

    $item->recuUcg->calculerTotal();
});

        static::updated(function ($item) {
            $item->recuUcg->calculerTotal();
        });

         static::deleting(function ($item) {
    if (!$item->isForceDeleting()) {
        if ($item->product_variant_id) {
            $variant = $item->variant;
            
            if ($variant) {
                $stockAvant = $variant->quantite_stock;
                $variant->increment('quantite_stock', $item->quantite);
                
                $produit = $variant->produit;
                $totalStock = $produit->variants()->sum('quantite_stock');
                $produit->update(['quantite_stock' => $totalStock]);

                StockMovement::create([
                    'produit_id'  => $item->produit_id,
                    'recu_ucg_id' => $item->recu_ucg_id,
                    'user_id'     => auth()->id(),
                    'type'        => 'retour',
                    'quantite'    => $item->quantite,
                    'stock_avant' => $stockAvant,
                    'stock_apres' => $variant->fresh()->quantite_stock,
                    'motif'       => "Suppression item variant ({$variant->variant_name})"
                ]);
            }
        } else {
            $produit = $item->produit;

            if ($produit) {
                $stockAvant = $produit->quantite_stock;
                
                // ✅ Passer achat_id pour restaurer le bon lot OU stock manuel
                self::restaurerStockFIFO(
                    $item->produit_id,
                    $item->quantite,
                    $item->achat_id  // ← null = stock manuel, int = lot spécifique
                );
                
                $produit->increment('quantite_stock', $item->quantite);

                StockMovement::create([
                    'produit_id'  => $produit->id,
                    'recu_ucg_id' => $item->recu_ucg_id,
                    'user_id'     => auth()->id(),
                    'type'        => 'retour',
                    'quantite'    => $item->quantite,
                    'stock_avant' => $stockAvant,
                    'stock_apres' => $produit->fresh()->quantite_stock,
                    'motif'       => $item->achat_id
                        ? "Annulation Lot #{$item->achat_id} - Reçu #{$item->recu_ucg_id}"
                        : "Annulation Stock Manuel - Reçu #{$item->recu_ucg_id}"
                ]);
            }
        }
    } else {
        Log::info("⚠️ Force delete détecté - Stock NON modifié pour item #{$item->id} (Produit: {$item->produit_nom}, Quantité: {$item->quantite})");
    }
});

        static::deleted(function ($item) {
        if ($item->recuUcg && !$item->isForceDeleting()) {
            $item->recuUcg->calculerTotal();
        }
    });



         // ✅ ✅ ✅ NOUVEAU EVENT - RESTORATION
        static::restored(function ($item) {
            Log::info("🔄 Restauration item #{$item->id} - Reçu #{$item->recu_ucg_id}");
            
            if ($item->product_variant_id) {
                // ✅ VARIANT - Vérifier stock puis décrémenter
                $variant = $item->variant;
                
                if ($variant) {
                    // Vérifier si stock suffisant
                    if ($variant->quantite_stock < $item->quantite) {
                        throw new \Exception("Stock insuffisant pour restaurer {$variant->full_name}. Stock actuel: {$variant->quantite_stock}, besoin: {$item->quantite}");
                    }
                    
                    $stockAvant = $variant->quantite_stock;
                    $variant->decrement('quantite_stock', $item->quantite);
                    
                    $produit = $variant->produit;
                    $totalStock = $produit->variants()->sum('quantite_stock');
                    $produit->update(['quantite_stock' => $totalStock]);

                    StockMovement::create([
                        'produit_id' => $item->produit_id,
                        'recu_ucg_id' => $item->recu_ucg_id,
                        'user_id' => auth()->id(),
                        'type' => 'sortie',
                        'quantite' => $item->quantite,
                        'stock_avant' => $stockAvant,
                        'stock_apres' => $variant->fresh()->quantite_stock,
                        'motif' => "Restauration variant ({$variant->variant_name}) - Reçu #{$item->recuUcg->numero_recu}",
                        'reference' => "RESTORE-VARIANT-{$variant->id}"
                    ]);
                    
                    Log::info("✅ Variant {$variant->variant_name} - Stock décrementé: {$stockAvant} → {$variant->fresh()->quantite_stock}");
                }
            } else {
                // ✅ PRODUIT SIMPLE - Vérifier stock puis décrémenter FIFO
                $produit = $item->produit;

                if ($produit) {
                    // Vérifier si stock suffisant
                    if ($produit->quantite_stock < $item->quantite) {
                        throw new \Exception("Stock insuffisant pour restaurer {$produit->nom}. Stock actuel: {$produit->quantite_stock}, besoin: {$item->quantite}");
                    }
                    
                    $stockAvant = $produit->quantite_stock;
                    
                    // ✅ Décrémenter stock FIFO
                    self::decrementerStockFIFO($item->produit_id, $item->quantite, $item->recu_ucg_id);
                    
                    // Décrémenter stock global
                    $produit->decrement('quantite_stock', $item->quantite);
                    $produit->increment('total_vendu', $item->quantite);

                    StockMovement::create([
                        'produit_id' => $produit->id,
                        'recu_ucg_id' => $item->recu_ucg_id,
                        'user_id' => auth()->id(),
                        'type' => 'sortie',
                        'quantite' => $item->quantite,
                        'stock_avant' => $stockAvant,
                        'stock_apres' => $produit->fresh()->quantite_stock,
                        'motif' => "Restauration FIFO - Reçu #{$item->recuUcg->numero_recu}"
                    ]);
                    
                    Log::info("✅ Produit {$produit->nom} - Stock décrementé: {$stockAvant} → {$produit->fresh()->quantite_stock}");
                }
            }

            // Recalculer total du reçu
            if ($item->recuUcg) {
                $item->recuUcg->calculerTotal();
                Log::info("✅ Total reçu recalculé: {$item->recuUcg->total} DH");
            }
        });

        static::forceDeleting(function ($item) {
        // ⚠️ CRITIQUE: Ne JAMAIS toucher au stock lors du force delete!
        // Le stock a déjà été restauré lors du soft delete (deleting event)
        
        Log::info("🗑️ PERMANENT DELETE: Item #{$item->id} - Produit: {$item->produit_nom} (Qté: {$item->quantite}) - Stock INCHANGÉ");
        
        // ✅ Pas de manipulation stock ici!
        // ✅ Pas de StockMovement création!
        // ✅ Juste du logging pour audit
    });
    }
    


    

    // ================================= MÉTHODES FIFO ==============================
    
    /**
     * ✅ MÉTHODE FIFO - Décrémenter stock mn les achats kadim
     */
   private static function decrementerStockFIFO($produitId, $quantiteVendue, $recuId, $achatIdChoisi = null)
{
    $produit = Produit::find($produitId);

    // ✅ CAS 1: Lot spécifique choisi
    if ($achatIdChoisi && $achatIdChoisi !== 'manuel') {
        $achat = Achat::find($achatIdChoisi);
        if ($achat && $achat->quantite_restante >= $quantiteVendue) {
            $achat->decrement('quantite_restante', $quantiteVendue);
            Log::info("✅ LOT SPÉCIFIQUE: -{$quantiteVendue} de l'achat #{$achat->id}");
            return;
        }
        // Si stock insuffisant dans ce lot → fallback FIFO
        Log::warning("⚠️ Lot #{$achatIdChoisi} insuffisant, fallback FIFO");
    }

    // ✅ CAS 2: Stock manuel
    $stockFifo   = Achat::where('produit_id', $produitId)->where('quantite_restante', '>', 0)->sum('quantite_restante');
    $stockManuel = max(0, $produit->quantite_stock - $stockFifo);

    if ($stockManuel > 0 && !$achatIdChoisi) {
        $quantiteManuel  = min($stockManuel, $quantiteVendue);
        $quantiteVendue -= $quantiteManuel;
        Log::info("✅ STOCK MANUEL: -{$quantiteManuel} unités");
        if ($quantiteVendue <= 0) return;
    }

    // ✅ CAS 3: FIFO normal
    $achats = Achat::where('produit_id', $produitId)
        ->where('quantite_restante', '>', 0)
        ->orderBy('date_achat', 'asc')
        ->get();

    foreach ($achats as $achat) {
        if ($quantiteVendue <= 0) break;
        if ($achat->quantite_restante >= $quantiteVendue) {
            $achat->decrement('quantite_restante', $quantiteVendue);
            $quantiteVendue = 0;
        } else {
            $quantiteVendue -= $achat->quantite_restante;
            $achat->update(['quantite_restante' => 0]);
        }
    }
}

    /**
     * ✅ Restaurer stock FIFO (inverse dial decrementerStockFIFO)
     */
  private static function restaurerStockFIFO($produitId, $quantite, $achatId = null)
{
    // ✅ CAS 1: Lot spécifique connu → restaurer ce lot exactement
    if ($achatId) {
        $achat = Achat::find($achatId);
        if ($achat) {
            $achat->increment('quantite_restante', $quantite);
            Log::info("✅ Restauration Lot #{$achatId}: +{$quantite} unités");
            return;
        }
    }

    // ✅ CAS 2: achat_id = null → c'était du stock manuel
    // Ne RIEN faire ici - le produit->quantite_stock sera incrémenté
    // par le deleting event automatiquement, ce qui restaure le stock manuel
    Log::info("✅ Restauration Stock Manuel: +{$quantite} unités (quantite_stock sera incrémenté par deleting event)");
    return;
}

    // ================================= MÉTHODES REMISE ==============================
    
    /**
     * ✅ CALCULER REMISE ET TOTAL APRÈS REMISE
     * Gère les remises en montant fixe OU en pourcentage
     */
    public function calculerRemise()
    {
        // Recalculer sous-total au cas où
        $this->sous_total = $this->quantite * $this->prix_unitaire;
        
        $montantRemise = 0;
        
        if ($this->remise_appliquee) {
            if ($this->remise_pourcentage > 0) {
                // Remise en pourcentage
                $montantRemise = ($this->sous_total * $this->remise_pourcentage) / 100;
                // Synchroniser remise_montant avec le calcul
                $this->remise_montant = $montantRemise;
            } elseif ($this->remise_montant > 0) {
                // Remise en montant fixe
                $montantRemise = $this->remise_montant;
            }
        }
        
        // Total après remise
        $this->total_apres_remise = max(0, $this->sous_total - $montantRemise);
        
        // Recalculer marge après remise (la remise diminue la marge)
        $this->marge_totale = (($this->prix_unitaire - $this->prix_achat) * $this->quantite) - $montantRemise;
        
        Log::info("🏷️ Remise calculée: " . ($this->remise_pourcentage > 0 ? "{$this->remise_pourcentage}%" : "{$montantRemise} DH") . " - Total après remise: {$this->total_apres_remise} DH");
    }
    
    /**
     * ✅ GET MONTANT REMISE RÉEL (Accessor)
     * Retourne le montant réel de la remise (calculé si pourcentage)
     */
    public function getMontantRemiseAttribute()
    {
        if (!$this->remise_appliquee) {
            return 0;
        }
        
        // Si remise en pourcentage, calculer le montant
        if ($this->attributes['remise_pourcentage'] > 0) {
            return ($this->sous_total * $this->attributes['remise_pourcentage']) / 100;
        }
        
        // Sinon retourner le montant fixe
        return $this->attributes['remise_montant'] ?? 0;
    }

    /**
     * ✅ MARGE APRÈS REMISE
     * Retourne la marge réelle après application de la remise
     */
    public function margeApresRemise(): float
    {
        if (!$this->remise_appliquee) {
            return $this->marge_totale;
        }
        
        // La marge est déjà ajustée dans calculerRemise()
        // Mais on peut aussi la calculer à la volée:
        $margeBase = ($this->prix_unitaire - $this->prix_achat) * $this->quantite;
        return max(0, $margeBase - $this->montant_remise);
    }

    /**
     * ✅ VÉRIFIE SI LA REMISE EST APPLIQUÉE SUR CET ITEM
     */
    public function aRemiseAppliquee(): bool
    {
        return (bool) $this->remise_appliquee;
    }
    
    /**
     * ✅ GET TYPE DE REMISE
     * Retourne 'pourcentage', 'montant' ou null
     */
    public function getTypeRemise(): ?string
    {
        if (!$this->remise_appliquee) {
            return null;
        }
        
        if ($this->remise_pourcentage > 0) {
            return 'pourcentage';
        }
        
        if ($this->remise_montant > 0) {
            return 'montant';
        }
        
        return null;
    }
    
    /**
     * ✅ GET VALEUR REMISE (pour affichage)
     * Retourne "15%" ou "50 DH"
     */
    public function getRemiseFormatee(): string
    {
        if (!$this->remise_appliquee) {
            return '-';
        }
        
        if ($this->remise_pourcentage > 0) {
            return number_format($this->remise_pourcentage, 2) . '%';
        }
        
        if ($this->remise_montant > 0) {
            return number_format($this->remise_montant, 2) . ' DH';
        }
        
        return '-';
    }
}