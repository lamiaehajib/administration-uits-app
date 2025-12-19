<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;
 protected $table = 'product_variants';
    protected $fillable = [
        'produit_id',
        'ram',
        'ssd',
        'cpu',
        'gpu',
        'ecran',
        'autres_specs',
        'prix_supplement',    // 🔥 NOUVEAU
        'quantite_stock',     // 🔥 NOUVEAU
        'variant_name',
        'sku',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'autres_specs' => 'array',
        'prix_supplement' => 'decimal:2',
        'quantite_stock' => 'integer',
    ];

    protected $appends = ['full_name', 'description_complete', 'prix_vente_final'];

    // ==================== BOOT ====================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($variant) {
            if (empty($variant->sku)) {
                $variant->sku = self::generateSKU($variant);
            }
            
            if (empty($variant->variant_name)) {
                $variant->variant_name = self::buildVariantName($variant);
            }
        });

        static::updating(function ($variant) {
            if ($variant->isDirty(['ram', 'ssd', 'cpu', 'gpu'])) {
                $variant->variant_name = self::buildVariantName($variant);
            }
        });
    }

    // ==================== RELATIONS ====================
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function recuItems()
    {
        return $this->hasMany(RecuItem::class, 'product_variant_id');
    }

    // ==================== ACCESSORS ====================
    
    /**
     * الاسم الكامل = اسم المنتج + المواصفات
     */
    public function getFullNameAttribute()
    {
        return $this->produit->nom . ' (' . $this->variant_name . ')';
    }

    /**
     * ✅ السعر النهائي = سعر المنتج الأساسي + السعر الإضافي
     */
    public function getPrixVenteFinalAttribute()
    {
        return $this->produit->prix_vente + $this->prix_supplement;
    }

    /**
     * ✅ سعر الشراء يبقى نفسه من المنتج الأساسي
     */
    public function getPrixAchatAttribute()
    {
        return $this->produit->prix_achat;
    }

    /**
     * ✅ المارج = السعر النهائي - سعر الشراء
     */
    public function getMargeUnitaireAttribute()
    {
        return $this->prix_vente_final - $this->prix_achat;
    }

    /**
     * ✅ تنبيه المخزون
     */
    public function getIsAlertStockAttribute()
    {
        return $this->quantite_stock <= ($this->produit->stock_alerte ?? 5);
    }

    /**
     * ✅ Description كاملة مع تبديل المواصفات
     */
    public function getDescriptionCompleteAttribute()
    {
        $baseDescription = $this->produit->description ?? '';
        $description = $baseDescription;
        
        // تبديل RAM
        if ($this->ram) {
            $description = preg_replace(
                '/RAM:\s*\d+GB/i', 
                'RAM: ' . $this->ram, 
                $description
            );
        }
        
        // تبديل SSD
        if ($this->ssd) {
            $description = preg_replace(
                '/SSD:\s*\d+GB\s*(NVMe)?/i', 
                'SSD: ' . $this->ssd, 
                $description
            );
        }
        
        // تبديل CPU
        if ($this->cpu) {
            $description = preg_replace(
                '/CPU:\s*[^\n]+/i', 
                'CPU: ' . $this->cpu, 
                $description
            );
        }
        
        // تبديل GPU
        if ($this->gpu) {
            $description = preg_replace(
                '/GPU [12]:\s*[^\n]+/i', 
                'GPU: ' . $this->gpu, 
                $description,
                1
            );
        }
        
        // تبديل Écran
        if ($this->ecran) {
            $description = preg_replace(
                '/Écran:\s*[^\n]+/i', 
                'Écran: ' . $this->ecran, 
                $description
            );
        }
        
        return $description;
    }

    // ==================== HELPERS ====================
    
    private static function generateSKU($variant)
    {
        $produit = Produit::find($variant->produit_id);
        $ref = $produit->reference ?? 'PROD';
        
        $ram = preg_replace('/[^0-9]/', '', $variant->ram ?? '0');
        $ssd = preg_replace('/[^0-9]/', '', $variant->ssd ?? '0');
        
        return strtoupper("{$ref}-R{$ram}S{$ssd}-" . substr(md5(uniqid()), 0, 4));
    }

    private static function buildVariantName($variant)
    {
        $parts = [];
        
        if ($variant->ram) {
            $parts[] = $variant->ram . ' RAM';
        }
        
        if ($variant->ssd) {
            $parts[] = $variant->ssd . ' SSD';
        }
        
        if ($variant->cpu) {
            $parts[] = $variant->cpu;
        }
        
        return implode(' / ', $parts) ?: 'Standard';
    }

    // ==================== SCOPES ====================
    
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function scopeEnStock($query)
    {
        return $query->where('quantite_stock', '>', 0);
    }

    public function scopeForProduit($query, $produitId)
    {
        return $query->where('produit_id', $produitId);
    }

    /**
     * ✅ Check si ce variant a déjà été vendu
     */
    public function hasVentes()
    {
        return $this->recuItems()->exists();
    }

    /**
     * ✅ Total vendu de ce variant spécifique
     */
    public function getTotalVenduAttribute()
    {
        return $this->recuItems()
            ->whereHas('recuUcg', function($q) {
                $q->whereIn('statut', ['en_cours', 'livre']);
            })
            ->sum('quantite');
    }
}