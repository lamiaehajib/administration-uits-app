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
            // Gérer les variants
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
                    
                    // ✅ FIFO - Khud prix_achat mn awwal achat disponible
                    if (empty($item->prix_achat)) {
                        $achatActif = Achat::where('produit_id', $variant->produit_id)
                            ->where('quantite_restante', '>', 0)
                            ->orderBy('date_achat', 'asc')
                            ->first();
                        
                        $item->prix_achat = $achatActif ? $achatActif->prix_achat : ($variant->prix_achat ?? 0);
                    }
                }
            } else {
                // ✅ FIFO - Produit Simple
                $produit = $item->produit;
                if ($produit) {
                    // ✅ Khud l'achat l9dam li 3ando stock
                    if (empty($item->prix_achat) || empty($item->prix_unitaire)) {
                        $achatActif = Achat::where('produit_id', $produit->id)
                            ->where('quantite_restante', '>', 0)
                            ->orderBy('date_achat', 'asc')
                            ->first();
                        
                        if ($achatActif) {
                            // ✅ Utiliser prix_achat & prix_vente_suggere du batch
                            $item->prix_achat = $achatActif->prix_achat;
                            $item->prix_unitaire = $achatActif->prix_vente_suggere ?? $produit->prix_vente;
                            
                            Log::info("🔍 FIFO: Achat #{$achatActif->id} - PA: {$achatActif->prix_achat} DH, PV: " . ($achatActif->prix_vente_suggere ?? $produit->prix_vente) . " DH");
                        } else {
                            // Fallback sur produit si pas d'achat disponible
                            $item->prix_achat = $produit->prix_achat ?? 0;
                            $item->prix_unitaire = $produit->prix_vente ?? 0;
                            
                            Log::warning("⚠️ FIFO: Pas d'achat disponible pour produit #{$produit->id}, utilisation prix par défaut");
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
            
            Log::info("💰 Calcul Marge: PV {$item->prix_unitaire} - PA {$item->prix_achat} = Marge {$item->marge_unitaire} DH/unité (Total: {$item->marge_totale} DH)");
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
        });

        static::deleted(function ($item) {
            if ($item->recuUcg) {
                $item->recuUcg->calculerTotal();
            }
        });
    }

    // ================================= MÉTHODES FIFO ==============================
    
    /**
     * ✅ MÉTHODE FIFO - Décrémenter stock mn les achats kadim
     */
    private static function decrementerStockFIFO($produitId, $quantiteVendue, $recuId)
    {
        // Khud les achats li 3andhom stock, triés mn l9dam
        $achats = Achat::where('produit_id', $produitId)
            ->where('quantite_restante', '>', 0)
            ->orderBy('date_achat', 'asc')
            ->get();

        $quantiteRestante = $quantiteVendue;

        foreach ($achats as $achat) {
            if ($quantiteRestante <= 0) {
                break; // Kamal kolchi
            }

            if ($achat->quantite_restante >= $quantiteRestante) {
                // Had l'achat 3ando bzaf, khud li bghitina
                $achat->decrement('quantite_restante', $quantiteRestante);
                
                Log::info("✅ FIFO Décrément: {$quantiteRestante} unités de l'achat #{$achat->id} (PA: {$achat->prix_achat} DH, PV: " . ($achat->prix_vente_suggere ?? 'N/A') . " DH) - Reçu #{$recuId}");
                
                $quantiteRestante = 0;
            } else {
                // Had l'achat ma3andoch bzaf, khud kolchi o kmal
                Log::info("⚠️ FIFO Épuisement: achat #{$achat->id} ({$achat->quantite_restante} unités, PA: {$achat->prix_achat} DH, PV: " . ($achat->prix_vente_suggere ?? 'N/A') . " DH) - Reçu #{$recuId}");
                
                $quantiteRestante -= $achat->quantite_restante;
                $achat->update(['quantite_restante' => 0]);
            }
        }

        // ✅ Safety check
        if ($quantiteRestante > 0) {
            Log::warning("⚠️ FIFO ALERTE: Manque {$quantiteRestante} unités dans les achats! Produit #{$produitId} - Vérifiez les données");
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

    // ================================= MÉTHODES EXISTANTES ==============================
    
    /**
     * ✅ MARGE APRÈS REMISE
     * Maintenant basée sur remise_appliquee plutôt que isPremierItem()
     */
    public function margeApresRemise(): float
    {
        $recu = $this->recuUcg;
        
        // Si remise appliquée SUR CET ITEM
        if ($recu && $recu->remise > 0 && $this->remise_appliquee) {
            return max(0, $this->marge_totale - $recu->remise);
        }
        
        return $this->marge_totale;
    }

    /**
     * ✅ VÉRIFIE SI LA REMISE EST APPLIQUÉE SUR CET ITEM
     */
    public function aRemiseAppliquee(): bool
    {
        return (bool) $this->remise_appliquee;
    }
}