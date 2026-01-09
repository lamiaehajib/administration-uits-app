<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'remise_appliquee', // ✅ NOUVEAU CHAMP
        'notes'
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'prix_achat' => 'decimal:2',
        'sous_total' => 'decimal:2',
        'marge_unitaire' => 'decimal:2',
        'marge_totale' => 'decimal:2',
        'remise_appliquee' => 'boolean', // ✅ CAST BOOLEAN
    ];

    // Relations (inchangées)
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
                    if (empty($item->prix_achat)) {
                        $item->prix_achat = $variant->prix_achat;
                    }
                }
            } else {
                $produit = $item->produit;
                if ($produit) {
                    if (empty($item->prix_unitaire)) {
                        $item->prix_unitaire = $produit->prix_vente ?? 0;
                    }
                    if (empty($item->prix_achat)) {
                        $item->prix_achat = $produit->prix_achat ?? 0;
                    }

                    $item->produit_nom = $produit->nom;
                    $item->produit_reference = $produit->reference;
                }
            }

            // Calcul de base (sans remise)
            $item->sous_total = $item->quantite * $item->prix_unitaire;
            $item->marge_unitaire = $item->prix_unitaire - $item->prix_achat;
            $item->marge_totale = $item->marge_unitaire * $item->quantite;
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
                $produit = $item->produit;

                if ($produit) {
                    $stockAvant = $produit->quantite_stock;
                    
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
                        'motif' => "Vente - Reçu #{$item->recuUcg->numero_recu}"
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
                $produit = $item->produit;

                if ($produit) {
                    $stockAvant = $produit->quantite_stock;
                    $produit->increment('quantite_stock', $item->quantite);

                    StockMovement::create([
                        'produit_id' => $produit->id,
                        'recu_ucg_id' => $item->recu_ucg_id,
                        'user_id' => auth()->id(),
                        'type' => 'retour',
                        'quantite' => $item->quantite,
                        'stock_avant' => $stockAvant,
                        'stock_apres' => $produit->fresh()->quantite_stock,
                        'motif' => "Suppression item"
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

    // ================================= MÉTHODES ==============================
    
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