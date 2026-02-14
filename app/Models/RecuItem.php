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
        'notes'
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
    ];

    // ================================= RELATIONS ==============================
    
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
    // Gérer les variants (makhdmin mazal)
    if ($item->product_variant_id) {
        $variant = ProductVariant::find($item->product_variant_id);
        if ($variant) {
            $item->produit_id = $variant->produit_id;
            $item->produit_nom = $variant->produit->nom;
            $item->produit_reference = $variant->produit->reference;
            $item->designation = $variant->variant_name;
            
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
        // ✅ PRODUIT SIMPLE - LOGIQUE HYBRIDE
        $produit = $item->produit;
        if ($produit) {
            // 🔍 Calculer stock total dans les achats
            $stockFifo = Achat::where('produit_id', $produit->id)
                ->where('quantite_restante', '>', 0)
                ->sum('quantite_restante');
            
            // 🎯 Stock "manuel" (créé sans achat)
            $stockManuel = max(0, $produit->quantite_stock - $stockFifo);
            
            Log::info("📊 Calcul Hybride Produit #{$produit->id}:");
            Log::info("   - Stock Total: {$produit->quantite_stock}");
            Log::info("   - Stock FIFO: {$stockFifo}");
            Log::info("   - Stock Manuel: {$stockManuel}");
            
            if (empty($item->prix_achat) || empty($item->prix_unitaire)) {
                // ✅ PRIORITÉ 1: Utiliser stock manuel SI disponible
                if ($stockManuel > 0) {
                    // Utiliser prix du produit (stock créé manuellement)
                    $item->prix_achat = $produit->prix_achat ?? 0;
                    $item->prix_unitaire = $produit->prix_vente ?? 0;
                    
                    Log::info("✅ STOCK MANUEL: PA: {$item->prix_achat} DH, PV: {$item->prix_unitaire} DH");
                } 
                // ✅ PRIORITÉ 2: Utiliser FIFO si stock manuel épuisé
                else {
                    $achatActif = Achat::where('produit_id', $produit->id)
                        ->where('quantite_restante', '>', 0)
                        ->orderBy('date_achat', 'asc')
                        ->first();
                    
                    if ($achatActif) {
                        $item->prix_achat = $achatActif->prix_achat;
                        $item->prix_unitaire = $achatActif->prix_vente_suggere ?? $produit->prix_vente;
                        
                        Log::info("✅ FIFO: Achat #{$achatActif->id} - PA: {$item->prix_achat} DH, PV: {$item->prix_unitaire} DH");
                    } else {
                        // Fallback sur produit si aucun achat disponible
                        $item->prix_achat = $produit->prix_achat ?? 0;
                        $item->prix_unitaire = $produit->prix_vente ?? 0;
                        
                        Log::warning("⚠️ Fallback: Pas d'achat ni stock manuel, utilisation prix par défaut");
                    }
                }
            }

            $item->produit_nom = $produit->nom;
            $item->produit_reference = $produit->reference;
        }
    }

    // Calcul de base (sans remise)
    $item->sous_total = $item->quantite * $item->prix_unitaire;
    $item->marge_unitaire = $item->prix_unitaire - $item->prix_achat;
    $item->marge_totale = $item->marge_unitaire * $item->quantite;
    
    // ✅ Calculer remise si appliquée
    if ($item->remise_appliquee) {
        $item->calculerRemise();
    } else {
        $item->total_apres_remise = $item->sous_total;
    }
    
    Log::info("💰 Calcul Marge: PV {$item->prix_unitaire} - PA {$item->prix_achat} = Marge {$item->marge_unitaire} DH/unité (Total: {$item->marge_totale} DH)");
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
                // Kod dial variant kaybqa nfso (ma3andokch FIFO f variants)
                $variant = $item->variant;
                
                if ($variant) {
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
                        'motif' => "Vente variant ({$variant->variant_name}) - Reçu #{$item->recuUcg->numero_recu}",
                        'reference' => "VARIANT-{$variant->id}"
                    ]);
                }
            } else {
                // ✅ FIFO - Produit Simple
                $produit = $item->produit;

                if ($produit) {
                    $stockAvant = $produit->quantite_stock;
                    
                    // ✅ Décrémenter stock FIFO (mn les achats kadim)
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
                        'motif' => "Vente FIFO - Reçu #{$item->recuUcg->numero_recu}"
                    ]);
                }
            }

            $item->recuUcg->calculerTotal();
        });

        static::updated(function ($item) {
            $item->recuUcg->calculerTotal();
        });

         static::deleting(function ($item) {
        // ✅ Restaurer stock UNIQUEMENT si soft delete
        if (!$item->isForceDeleting()) {
            if ($item->product_variant_id) {
                // Kod dial variant kaybqa nfso
                $variant = $item->variant;
                
                if ($variant) {
                    $stockAvant = $variant->quantite_stock;
                    $variant->increment('quantite_stock', $item->quantite);
                    
                    $produit = $variant->produit;
                    $totalStock = $produit->variants()->sum('quantite_stock');
                    $produit->update(['quantite_stock' => $totalStock]);

                    StockMovement::create([
                        'produit_id' => $item->produit_id,
                        'recu_ucg_id' => $item->recu_ucg_id,
                        'user_id' => auth()->id(),
                        'type' => 'retour',
                        'quantite' => $item->quantite,
                        'stock_avant' => $stockAvant,
                        'stock_apres' => $variant->fresh()->quantite_stock,
                        'motif' => "Suppression item variant ({$variant->variant_name})"
                    ]);
                }
            } else {
                // ✅ FIFO - Restaurer stock (f awwal achat)
                $produit = $item->produit;

                if ($produit) {
                    $stockAvant = $produit->quantite_stock;
                    
                    // ✅ Restaurer quantite_restante f l'achat l9dam
                    self::restaurerStockFIFO($item->produit_id, $item->quantite);
                    
                    // Incrémenter stock global
                    $produit->increment('quantite_stock', $item->quantite);

                    StockMovement::create([
                        'produit_id' => $produit->id,
                        'recu_ucg_id' => $item->recu_ucg_id,
                        'user_id' => auth()->id(),
                        'type' => 'retour',
                        'quantite' => $item->quantite,
                        'stock_avant' => $stockAvant,
                        'stock_apres' => $produit->fresh()->quantite_stock,
                        'motif' => "Suppression item FIFO"
                    ]);
                }
            }
        } else {
            // ✅ Force delete - AUCUNE modification stock
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
    private static function decrementerStockFIFO($produitId, $quantiteVendue, $recuId)
{
    $produit = Produit::find($produitId);
    
    // 🔍 Calculer stock manuel
    $stockFifo = Achat::where('produit_id', $produitId)
        ->where('quantite_restante', '>', 0)
        ->sum('quantite_restante');
    
    $stockManuel = max(0, $produit->quantite_stock - $stockFifo);
    
    // ✅ PRIORITÉ 1: Décrémenter stock manuel d'abord
    if ($stockManuel > 0) {
        $quantiteManuel = min($stockManuel, $quantiteVendue);
        
        Log::info("✅ STOCK MANUEL: -{$quantiteManuel} unités (PA: {$produit->prix_achat} DH, PV: {$produit->prix_vente} DH) - Reçu #{$recuId}");
        
        $quantiteVendue -= $quantiteManuel;
        
        // Si tout vendu depuis stock manuel, on sort
        if ($quantiteVendue <= 0) {
            return;
        }
    }
    
    // ✅ PRIORITÉ 2: Décrémenter FIFO (achats)
    $achats = Achat::where('produit_id', $produitId)
        ->where('quantite_restante', '>', 0)
        ->orderBy('date_achat', 'asc')
        ->get();

    foreach ($achats as $achat) {
        if ($quantiteVendue <= 0) {
            break;
        }

        if ($achat->quantite_restante >= $quantiteVendue) {
            $achat->decrement('quantite_restante', $quantiteVendue);
            
            Log::info("✅ FIFO Décrément: {$quantiteVendue} unités de l'achat #{$achat->id} (PA: {$achat->prix_achat} DH) - Reçu #{$recuId}");
            
            $quantiteVendue = 0;
        } else {
            Log::info("⚠️ FIFO Épuisement: achat #{$achat->id} ({$achat->quantite_restante} unités) - Reçu #{$recuId}");
            
            $quantiteVendue -= $achat->quantite_restante;
            $achat->update(['quantite_restante' => 0]);
        }
    }

    if ($quantiteVendue > 0) {
        Log::warning("⚠️ ALERTE: Manque {$quantiteVendue} unités! Produit #{$produitId}");
    }
}

    /**
     * ✅ Restaurer stock FIFO (inverse dial decrementerStockFIFO)
     */
    private static function restaurerStockFIFO($produitId, $quantite)
    {
        // Khud l'achat l9dam (même logique)
        $achat = Achat::where('produit_id', $produitId)
            ->orderBy('date_achat', 'asc')
            ->first();

        if ($achat) {
            $achat->increment('quantite_restante', $quantite);
            Log::info("✅ FIFO Restauration: +{$quantite} unités à l'achat #{$achat->id} (PA: {$achat->prix_achat} DH, PV: " . ($achat->prix_vente_suggere ?? 'N/A') . " DH)");
        } else {
            Log::warning("⚠️ FIFO Restauration: Aucun achat trouvé pour le produit #{$produitId}");
        }
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