<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class RecuItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'recu_ucg_id', 'produit_id', 'produit_nom', 'produit_reference',
        'quantite', 'prix_unitaire', 'prix_achat',
        'sous_total', 'marge_unitaire', 'marge_totale', 'notes'
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

    // 🎯 Boot Events
    protected static function boot()
    {
        parent::boot();

        // Avant création
        static::creating(function ($item) {
            $produit = $item->produit;

            if (!$produit) {
                throw new \Exception("Produit non trouvez!");
            }

            // Vérifier stock
            if ($produit->quantite_stock < $item->quantite) {
                throw new \Exception("Stock ma kafich! Stock: {$produit->quantite_stock}");
            }

            // Auto-remplir
            if (empty($item->prix_unitaire)) {
                $item->prix_unitaire = $produit->prix_vente ?? $produit->prix_vendu ?? 0;
            }
            
            if (empty($item->prix_achat)) {
                $item->prix_achat = $produit->prix_achat ?? 0;
            }

            $item->produit_nom = $produit->nom;
            $item->produit_reference = $produit->reference;
            $item->sous_total = $item->quantite * $item->prix_unitaire;
            $item->marge_unitaire = $item->prix_unitaire - $item->prix_achat;
            $item->marge_totale = $item->marge_unitaire * $item->quantite;
        });

        // Après création - Diminuer stock AUTOMATIQUEMENT
        static::created(function ($item) {
            $produit = $item->produit;

            if ($produit) {
                $stockAvant = $produit->quantite_stock;
                
                // Diminuer stock
                $produit->decrement('quantite_stock', $item->quantite);
                $produit->increment('total_vendu', $item->quantite);

                // Enregistrer mouvement
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

            // Recalculer total reçu
            $item->recuUcg->calculerTotal();
        });

        // Après mise à jour
        static::updated(function ($item) {
            $item->recuUcg->calculerTotal();
        });

        // Avant suppression
        static::deleting(function ($item) {
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
        });
    }
}