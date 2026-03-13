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
        'remise_montant',
        'remise_pourcentage',
        'total_apres_remise',
        'notes',
        'achat_id',
        // ✅ Gift
        'is_gift',
        'prix_original',
    ];

    protected $casts = [
        'quantite'           => 'integer',
        'prix_unitaire'      => 'decimal:2',
        'prix_achat'         => 'decimal:2',
        'sous_total'         => 'decimal:2',
        'marge_unitaire'     => 'decimal:2',
        'marge_totale'       => 'decimal:2',
        'remise_appliquee'   => 'boolean',
        'remise_montant'     => 'decimal:2',
        'remise_pourcentage' => 'decimal:2',
        'total_apres_remise' => 'decimal:2',
        'achat_id'           => 'integer',
        // ✅ Gift
        'is_gift'            => 'boolean',
        'prix_original'      => 'decimal:2',
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
                    $item->produit_id        = $variant->produit_id;
                    $item->produit_nom       = $variant->produit->nom;
                    $item->produit_reference = $variant->produit->reference;
                    $item->designation       = $variant->variant_name;

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

                    if (!empty($item->achat_id) && $item->achat_id !== 'manuel') {
                        $achat = Achat::find($item->achat_id);
                        if ($achat) {
                            $item->prix_achat    = $achat->prix_achat;
                            $item->prix_unitaire = $achat->prix_vente_suggere ?? $produit->prix_vente;
                        }
                    } elseif ($item->achat_id === 'manuel') {
                        $item->prix_achat    = $produit->prix_achat ?? 0;
                        $item->prix_unitaire = $produit->prix_vente ?? 0;
                        $item->achat_id      = null;
                    } elseif (!empty($item->prix_unitaire) && !empty($item->prix_achat)) {
                        // Prix fournis — rien à faire
                    } else {
                        $stockFifo   = Achat::where('produit_id', $produit->id)->where('quantite_restante', '>', 0)->sum('quantite_restante');
                        $stockManuel = max(0, $produit->quantite_stock - $stockFifo);

                        if ($stockManuel > 0) {
                            $item->prix_achat    = $produit->prix_achat ?? 0;
                            $item->prix_unitaire = $produit->prix_vente ?? 0;
                        } else {
                            $achatActif = Achat::where('produit_id', $produit->id)
                                ->where('quantite_restante', '>', 0)
                                ->orderBy('date_achat', 'asc')
                                ->first();
                            if ($achatActif) {
                                $item->prix_achat    = $achatActif->prix_achat;
                                $item->prix_unitaire = $achatActif->prix_vente_suggere ?? $produit->prix_vente;
                                $item->achat_id      = $achatActif->id;
                            } else {
                                $item->prix_achat    = $produit->prix_achat ?? 0;
                                $item->prix_unitaire = $produit->prix_vente ?? 0;
                            }
                        }
                    }
                }
            }

            if ($item->is_gift) {
    // Sauvegarder le prix original avant de le mettre à 0
    $item->prix_original = $item->prix_unitaire;
 
    // Prix de vente = 0 (c'est un cadeau)
    $item->prix_unitaire = 0;
 
    // ✅ FIX MARGE: Gift = marge 0 (pas de vente, pas de perte comptable sur la ligne)
    // Le coût réel est enregistré comme CHARGE séparée
    $item->sous_total         = 0;
    $item->marge_unitaire     = 0;   // ← était: 0 - prix_achat (FAUX)
    $item->marge_totale       = 0;   // ← était: négatif (FAUX)
    $item->total_apres_remise = 0;
    $item->remise_appliquee   = false;
    $item->remise_montant     = 0;
    $item->remise_pourcentage = 0;
 
    \Illuminate\Support\Facades\Log::info("🎁 GIFT créé: {$item->produit_nom} x{$item->quantite} | Prix original: {$item->prix_original} DH | Prix achat: {$item->prix_achat} DH");
} else {
                // === CALCULS NORMAUX ===
                $item->sous_total     = $item->quantite * $item->prix_unitaire;
                $item->marge_unitaire = $item->prix_unitaire - $item->prix_achat;
                $item->marge_totale   = $item->marge_unitaire * $item->quantite;

                if ($item->remise_appliquee) {
                    $item->calculerRemise();
                } else {
                    $item->total_apres_remise = $item->sous_total;
                }
            }
        });

        static::updating(function ($item) {
            if ($item->isDirty(['remise_appliquee', 'remise_montant', 'remise_pourcentage', 'quantite', 'prix_unitaire'])) {
                $item->sous_total     = $item->quantite * $item->prix_unitaire;
                $item->marge_unitaire = $item->prix_unitaire - $item->prix_achat;
                $item->marge_totale   = $item->marge_unitaire * $item->quantite;

                if ($item->remise_appliquee) {
                    $item->calculerRemise();
                } else {
                    $item->total_apres_remise = $item->sous_total;
                    $item->remise_montant     = 0;
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

                    $produit    = $variant->produit;
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
                        'motif'       => $item->is_gift
                            ? "🎁 GIFT variant ({$variant->variant_name}) - Reçu #{$item->recuUcg->numero_recu}"
                            : "Vente variant ({$variant->variant_name}) - Reçu #{$item->recuUcg->numero_recu}",
                        'reference'   => "VARIANT-{$variant->id}",
                    ]);
                }
            } else {
                $produit = $item->produit;
                if ($produit) {
                    $stockAvant = $produit->quantite_stock;

                    self::decrementerStockFIFO(
                        $item->produit_id,
                        $item->quantite,
                        $item->recu_ucg_id,
                        $item->achat_id
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
                        'motif'       => $item->is_gift
                            ? "🎁 GIFT - Reçu #{$item->recuUcg->numero_recu}"
                            : ($item->achat_id
                                ? "Vente Lot #{$item->achat_id} - Reçu #{$item->recuUcg->numero_recu}"
                                : "Vente FIFO - Reçu #{$item->recuUcg->numero_recu}"),
                    ]);

                    // ✅ GIFT → Créer automatiquement une charge variable
                    if ($item->is_gift) {
                        self::creerChargeGift($item);
                    }
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

                        $produit    = $variant->produit;
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
                            'motif'       => "Suppression item variant ({$variant->variant_name})",
                        ]);
                    }
                } else {
                    $produit = $item->produit;
                    if ($produit) {
                        $stockAvant = $produit->quantite_stock;

                        self::restaurerStockFIFO($item->produit_id, $item->quantite, $item->achat_id);
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
                                : "Annulation Stock Manuel - Reçu #{$item->recu_ucg_id}",
                        ]);

                        // ✅ GIFT → Supprimer la charge associée
                        if ($item->is_gift) {
                            Charge::where('recu_item_id_gift', $item->id)->delete();
                            Log::info("🗑️ Charge gift supprimée pour item #{$item->id}");
                        }
                    }
                }
            } else {
                Log::info("⚠️ Force delete détecté - Stock NON modifié pour item #{$item->id}");
            }
        });

        static::deleted(function ($item) {
            if ($item->recuUcg && !$item->isForceDeleting()) {
                $item->recuUcg->calculerTotal();
            }
        });

        static::restored(function ($item) {
            Log::info("🔄 Restauration item #{$item->id} - Reçu #{$item->recu_ucg_id}");

            if ($item->product_variant_id) {
                $variant = $item->variant;
                if ($variant) {
                    if ($variant->quantite_stock < $item->quantite) {
                        throw new \Exception("Stock insuffisant pour restaurer {$variant->full_name}.");
                    }
                    $stockAvant = $variant->quantite_stock;
                    $variant->decrement('quantite_stock', $item->quantite);
                    $produit    = $variant->produit;
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
                        'motif'       => "Restauration variant ({$variant->variant_name}) - Reçu #{$item->recuUcg->numero_recu}",
                        'reference'   => "RESTORE-VARIANT-{$variant->id}",
                    ]);
                }
            } else {
                $produit = $item->produit;
                if ($produit) {
                    if ($produit->quantite_stock < $item->quantite) {
                        throw new \Exception("Stock insuffisant pour restaurer {$produit->nom}.");
                    }
                    $stockAvant = $produit->quantite_stock;
                    self::decrementerStockFIFO($item->produit_id, $item->quantite, $item->recu_ucg_id);
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
                        'motif'       => "Restauration FIFO - Reçu #{$item->recuUcg->numero_recu}",
                    ]);

                    // ✅ GIFT restauré → recréer la charge
                    if ($item->is_gift) {
                        self::creerChargeGift($item);
                    }
                }
            }

            if ($item->recuUcg) {
                $item->recuUcg->calculerTotal();
            }
        });

        static::forceDeleting(function ($item) {
            Log::info("🗑️ PERMANENT DELETE: Item #{$item->id} - {$item->produit_nom} (Stock INCHANGÉ)");
        });
    }

    // ================================= GIFT - CHARGE AUTO ==============================

    /**
     * ✅ Créer automatiquement une charge variable quand un gift est ajouté
     * La valeur = prix_achat × quantite (coût réel du cadeau)
     */
    private static function creerChargeGift(RecuItem $item): void
{
    try {
        $montant = $item->prix_achat * $item->quantite;
        $recu    = $item->recuUcg;
 
        // ✅ FIX CATÉGORIE: chercher "GIFT" en priorité, créer si inexistant
        $chargeCategory = \App\Models\ChargeCategory::where(function ($q) {
            $q->where('nom', 'like', '%gift%')
              ->orWhere('nom', 'like', '%Gift%')
              ->orWhere('nom', 'like', '%GIFT%')
              ->orWhere('nom', 'like', '%cadeau%')
              ->orWhere('nom', 'like', '%Cadeau%');
        })->first();
 
        // ✅ Si aucune catégorie GIFT n'existe → la créer automatiquement
        if (!$chargeCategory) {
            $chargeCategory = \App\Models\ChargeCategory::create([
                'nom'         => 'GIFT',
                'type_defaut' => 'variable',
                'description' => 'Charges liées aux cadeaux offerts aux clients',
                'couleur'     => '#28a745', // vert — optionnel si ton model a ce champ
            ]);
            \Illuminate\Support\Facades\Log::info("✅ Catégorie GIFT créée automatiquement (id: {$chargeCategory->id})");
        }
 
        Charge::create([
            'libelle'            => "🎁 Gift: {$item->produit_nom} x{$item->quantite} — Reçu #{$recu->numero_recu}",
            'description'        => "Accessoire offert comme cadeau au client {$recu->client_nom}. Prix achat unitaire: {$item->prix_achat} DH.",
            'type'               => 'variable',
            'charge_category_id' => $chargeCategory->id,   // ✅ toujours catégorie GIFT
            'montant'            => $montant,
            'date_charge'        => now()->toDateString(),
            'mode_paiement'      => 'especes',
            'statut_paiement'    => 'paye',
            'montant_paye'       => $montant,
            'fournisseur'        => 'Stock interne',
            'notes'              => "Auto-généré depuis reçu #{$recu->numero_recu} — Produit ID: {$item->produit_id}",
            'user_id'            => auth()->id() ?? $recu->user_id,
            'recu_item_id_gift'  => $item->id,
        ]);
 
        \Illuminate\Support\Facades\Log::info("✅ Charge gift créée: {$montant} DH pour {$item->produit_nom} x{$item->quantite} → catégorie: {$chargeCategory->nom}");
 
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error("❌ Erreur création charge gift: " . $e->getMessage());
        // On ne throw pas — le reçu doit quand même être créé
    }
}

    // ================================= MÉTHODES FIFO ==============================

    private static function decrementerStockFIFO($produitId, $quantiteVendue, $recuId, $achatIdChoisi = null)
    {
        $produit = Produit::find($produitId);

        if ($achatIdChoisi && $achatIdChoisi !== 'manuel') {
            $achat = Achat::find($achatIdChoisi);
            if ($achat && $achat->quantite_restante >= $quantiteVendue) {
                $achat->decrement('quantite_restante', $quantiteVendue);
                return;
            }
            Log::warning("⚠️ Lot #{$achatIdChoisi} insuffisant, fallback FIFO");
        }

        $stockFifo   = Achat::where('produit_id', $produitId)->where('quantite_restante', '>', 0)->sum('quantite_restante');
        $stockManuel = max(0, $produit->quantite_stock - $stockFifo);

        if ($stockManuel > 0 && !$achatIdChoisi) {
            $quantiteManuel  = min($stockManuel, $quantiteVendue);
            $quantiteVendue -= $quantiteManuel;
            if ($quantiteVendue <= 0) return;
        }

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

    private static function restaurerStockFIFO($produitId, $quantite, $achatId = null)
    {
        if ($achatId) {
            $achat = Achat::find($achatId);
            if ($achat) {
                $achat->increment('quantite_restante', $quantite);
                return;
            }
        }
        Log::info("✅ Restauration Stock Manuel: +{$quantite} unités");
    }

    // ================================= MÉTHODES REMISE ==============================

    public function calculerRemise()
    {
        $this->sous_total  = $this->quantite * $this->prix_unitaire;
        $montantRemise     = 0;

        if ($this->remise_appliquee) {
            if ($this->remise_pourcentage > 0) {
                $montantRemise       = ($this->sous_total * $this->remise_pourcentage) / 100;
                $this->remise_montant = $montantRemise;
            } elseif ($this->remise_montant > 0) {
                $montantRemise = $this->remise_montant;
            }
        }

        $this->total_apres_remise = max(0, $this->sous_total - $montantRemise);
        $this->marge_totale       = (($this->prix_unitaire - $this->prix_achat) * $this->quantite) - $montantRemise;
    }

    public function getMontantRemiseAttribute()
    {
        if (!$this->remise_appliquee) return 0;
        if ($this->attributes['remise_pourcentage'] > 0) {
            return ($this->sous_total * $this->attributes['remise_pourcentage']) / 100;
        }
        return $this->attributes['remise_montant'] ?? 0;
    }

    public function margeApresRemise(): float
    {
        if (!$this->remise_appliquee) return $this->marge_totale;
        $margeBase = ($this->prix_unitaire - $this->prix_achat) * $this->quantite;
        return max(0, $margeBase - $this->montant_remise);
    }

    public function aRemiseAppliquee(): bool
    {
        return (bool) $this->remise_appliquee;
    }

    public function getTypeRemise(): ?string
    {
        if (!$this->remise_appliquee) return null;
        if ($this->remise_pourcentage > 0) return 'pourcentage';
        if ($this->remise_montant > 0) return 'montant';
        return null;
    }

    public function getRemiseFormatee(): string
    {
        if (!$this->remise_appliquee) return '-';
        if ($this->remise_pourcentage > 0) return number_format($this->remise_pourcentage, 2) . '%';
        if ($this->remise_montant > 0) return number_format($this->remise_montant, 2) . ' DH';
        return '-';
    }
}