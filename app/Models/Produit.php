<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;

class Produit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'reference',
        'description',
        'category_id',
        'prix_achat',
        'prix_vente',
        'quantite_stock',
        'stock_alerte',
        'total_vendu',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'prix_achat' => 'decimal:2',
        'prix_vente' => 'decimal:2',
        'quantite_stock' => 'integer',
        'stock_alerte' => 'integer',
        'total_vendu' => 'integer',
    ];

    // ==================== RELATIONS ====================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }

    /**
     * ✅ هاد العلاقة مهمة جداً للنظام الجديد
     */
    public function recuItems()
    {
        return $this->hasMany(RecuItem::class);
    }

    /**
     * ✅ لتتبع حركات المخزون
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function variants()
{
    // On précise 'produit_id' au cas où Laravel ne le devine pas
    return $this->hasMany(ProductVariant::class, 'produit_id');
}

    // ==================== ACCESSORS ====================

    public function getMargeUnitaireAttribute()
    {
        return $this->prix_vente - $this->prix_achat;
    }

    public function getMargePourcentageAttribute()
    {
        if ($this->prix_achat == 0) {
            return 0;
        }
        return (($this->prix_vente - $this->prix_achat) / $this->prix_achat) * 100;
    }

    public function getIsAlertStockAttribute()
    {
        return $this->quantite_stock <= $this->stock_alerte;
    }

    public function getIsRuptureAttribute()
    {
        return $this->quantite_stock == 0;
    }

    public function getValeurStockAttribute()
    {
        return $this->quantite_stock * $this->prix_achat;
    }

    public function getValeurVentePotentielleAttribute()
    {
        return $this->quantite_stock * $this->prix_vente;
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

    public function scopeAlertStock($query)
    {
        return $query->whereRaw('quantite_stock <= stock_alerte');
    }

    public function scopeRupture($query)
    {
        return $query->where('quantite_stock', 0);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('reference', 'like', "%{$search}%");
        });
    }
    public function scopeOnlyTrashed($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * ✅ لجلب جميع المنتجات (النشطة والمحذوفة)
     */
    public function scopeWithTrashed($query)
    {
        return $query->withTrashed();
    }


    protected static function booted()
{
    static::updated(function ($produit) {
        if ($produit->isDirty('quantite_stock')) {
            
            $url = env('WOOCOMMERCE_STORE_URL', 'https://ucgs.ma');
            $ck = env('WOOCOMMERCE_CONSUMER_KEY');
            $cs = env('WOOCOMMERCE_CONSUMER_SECRET');

            \Illuminate\Support\Facades\Log::info("🔄 محاولة مزامنة المخزون لـ: " . $produit->reference);

            try {
                $fullUrl = rtrim($url, '/') . '/wp-json/wc/v3/products';
                
                // كنحاول نلقاو المنتج
                $response = \Illuminate\Support\Facades\Http::withBasicAuth($ck, $cs)
                    ->timeout(5) // مهلة قصيرة باش ميتعطلش الـ App
                    ->get($fullUrl, ['sku' => $produit->reference]);

                if ($response->successful()) {
                    $wooProduct = $response->json()[0] ?? null;

                    if ($wooProduct) {
                        // إيلا لقى المنتج، كيدير التحديث
                        \Illuminate\Support\Facades\Http::withBasicAuth($ck, $cs)
                            ->put($fullUrl . '/' . $wooProduct['id'], [
                                'stock_quantity' => (int)$produit->quantite_stock,
                                'manage_stock' => true
                            ]);
                        \Illuminate\Support\Facades\Log::info("✅ تم تحديث الموقع بنجاح.");
                    } else {
                        // إيلا ملقاش المنتج، كيسجل غير ملاحظة ومكيحبسش الـ App
                        \Illuminate\Support\Facades\Log::warning("⚠️ المنتج {$produit->reference} غير موجود في الموقع حالياً. سيتم تحديثه يدوياً لاحقاً.");
                    }
                }
            } catch (\Exception $e) {
                // إيلا كاين مشكل في الكونيكسيون، كيدوز عادي
                \Illuminate\Support\Facades\Log::error("🚨 مشكل اتصال مع WooCommerce: " . $e->getMessage());
            }
        }
    });
}
}