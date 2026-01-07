<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'parent_id'];

    // ==================== RELATIONS ====================
    
    /**
     * Catégorie parent (si existe)
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Sous-catégories (children)
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Tous les descendants (récursif)
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Produits de cette catégorie
     */
    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    /**
     * Tous les produits (incluant ceux des sous-catégories)
     */
    public function allProduits()
    {
        $produits = $this->produits;
        
        foreach ($this->children as $child) {
            $produits = $produits->merge($child->allProduits());
        }
        
        return $produits;
    }

    // ==================== SCOPES ====================
    
    /**
     * Seulement les catégories principales (sans parent)
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Seulement les sous-catégories
     */
    public function scopeChildren($query)
    {
        return $query->whereNotNull('parent_id');
    }

    // ==================== ACCESSORS ====================
    
    /**
     * Vérifier si c'est une catégorie parent
     */
    public function getIsParentAttribute()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Obtenir le chemin complet (ex: "Parent > Enfant")
     */
    public function getFullPathAttribute()
    {
        $path = [$this->nom];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->nom);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
    }

    /**
     * Compter les produits totaux (avec sous-catégories)
     */
    public function getTotalProduitsCountAttribute()
    {
        return $this->allProduits()->count();
    }
}