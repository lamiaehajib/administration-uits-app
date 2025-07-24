<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactureItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'factures_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'factures_id',
        'libele',
        'quantite',
        'prix_ht',
        'prix_total',
    ];

    /**
     * Get the facture that owns the item.
     */
    public function facture()
    {
        return $this->belongsTo(Facture::class, 'factures_id');
    }
}
