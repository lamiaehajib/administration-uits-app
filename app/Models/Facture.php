<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facture extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'factures';

    protected $fillable = [
        'facture_num',
        'type', // ✅ NOUVEAU
        'date',
        'titre',
        'client',
        'ice',
        'adresse',
        'ref',
        'total_ht',
        'tva',
        'total_ttc',
        'vide',
        'important',
        'user_id',
        'afficher_cachet',
        'currency',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(FactureItem::class, 'factures_id');
    }

    public function dashboard(): HasMany
    {
        return $this->hasMany(Dashboard::class, 'factures_id');
    }

    public function importantInfoo(): HasMany
    {
        return $this->hasMany(ImportantInfoo::class, 'factures_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Scopes
    public function scopeServices($query)
    {
        return $query->where('type', 'service');
    }

    public function scopeProduits($query)
    {
        return $query->where('type', 'produit');
    }
}