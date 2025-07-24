<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'factures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facture_num',
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


    /**
     * Get the items for the facture.
     */
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

}



