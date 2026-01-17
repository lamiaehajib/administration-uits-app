<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChargeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'description',
        'type_defaut',
        'icone',
        'couleur',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // ================================= RELATIONS ==============================
    
    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class, 'charge_category_id');
    }

    // ================================= SCOPES ==============================
    
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    // ================================= BOOT ==============================
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->code)) {
                $category->code = self::generateCode();
            }
        });
    }

    // ================================= MÉTHODES ==============================
    
    public static function generateCode(): string
    {
        $lastCategory = self::latest()->first();
        $number = $lastCategory ? intval(substr($lastCategory->code, 4)) + 1 : 1;
        return sprintf('CAT-%03d', $number);
    }

    /**
     * Total des charges pour cette catégorie
     */
    public function totalCharges($dateDebut = null, $dateFin = null): float
    {
        $query = $this->charges()->where('statut_paiement', Charge::STATUT_PAYE);
        
        if ($dateDebut && $dateFin) {
            $query->entreDates($dateDebut, $dateFin);
        }
        
        return (float) $query->sum('montant');
    }
}