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
        'product_variant_id', // ✅ NOUVEAU
        'produit_nom', 
        'produit_reference',
        'designation', // ✅ Pour afficher le variant (RAM/SSD/CPU)
        'quantite', 
        'prix_unitaire', 
        'prix_achat',
        'sous_total', 
        'marge_unitaire', 
        'marge_totale', 
        'notes'
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
        'prix_achat' => 'decimal:2',
        'sous_total' => 'decimal:2',
        'marge_unitaire' => 'decimal:2',
        'marge_totale' => 'decimal:2',
    ];

    // 🔗 Relations
    public function recuUcg()
    {
        return $this->belongsTo(RecuUcg::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    // ✅ NOUVELLE RELATION
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // 🎯 Boot Events
    protected static function boot()
    {
        parent::boot();

        // Avant création
        static::creating(function ($item) {
    if ($item->product_variant_id) {
        $variant = ProductVariant::find($item->product_variant_id);
        if ($variant) {
            // ✅ تم إزالة رمي الخطأ (throw Exception) للسماح بإنشاء الإيصال
            $item->produit_id = $variant->produit_id;
            $item->produit_nom = $variant->produit->nom;
            $item->produit_reference = $variant->produit->reference;
            $item->designation = $variant->variant_name;
            
            if (empty($item->prix_unitaire)) $item->prix_unitaire = $variant->prix_vente_final;
            if (empty($item->prix_achat)) $item->prix_achat = $variant->prix_achat;
        }
    } else {
        $produit = $item->produit;
        if ($produit) {
            // ✅ تم إزالة التحقق من المخزون الذي يسبب خطأ 500
            if (empty($item->prix_unitaire)) $item->prix_unitaire = $produit->prix_vente ?? 0;
            if (empty($item->prix_achat)) $item->prix_achat = $produit->prix_achat ?? 0;

            $item->produit_nom = $produit->nom;
            $item->produit_reference = $produit->reference;
        }
    }

    // الحسابات تبقى كما هي
    $item->sous_total = $item->quantite * $item->prix_unitaire;
    $item->marge_unitaire = $item->prix_unitaire - $item->prix_achat;
    $item->marge_totale = $item->marge_unitaire * $item->quantite;
});

        // Après création - Diminuer stock
        static::created(function ($item) {
            if ($item->product_variant_id) {
                // ✅ Gérer stock du variant
                $variant = $item->variant;
                
                if ($variant) {
                    $stockAvant = $variant->quantite_stock;
                    
                    // Diminuer stock du variant
                    $variant->decrement('quantite_stock', $item->quantite);
                    
                    // Mettre à jour le stock total du produit parent
                    $produit = $variant->produit;
                    $totalStock = $produit->variants()->sum('quantite_stock');
                    $produit->update(['quantite_stock' => $totalStock]);

                    // Enregistrer mouvement de stock
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
                // Produit classique
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

            // Recalculer total reçu
            $item->recuUcg->calculerTotal();
        });

        // Après mise à jour
        static::updated(function ($item) {
            $item->recuUcg->calculerTotal();
        });

        // Avant suppression - Remettre stock
        static::deleting(function ($item) {
            if ($item->product_variant_id) {
                // ✅ Restaurer stock du variant
                $variant = $item->variant;
                
                if ($variant) {
                    $stockAvant = $variant->quantite_stock;
                    $variant->increment('quantite_stock', $item->quantite);
                    
                    // Mettre à jour le stock total du produit parent
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
                // Produit classique
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
    }
}