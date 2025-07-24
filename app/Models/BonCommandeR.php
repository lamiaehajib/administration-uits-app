<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonCommandeR extends Model
{
    protected $table = 'bon_commande_r';

    protected $fillable = [
        'bon_num',
        'titre',
        'prestataire',
        'tele',
        'ice',
        'adresse',
        'ref',
        'important',
        'total_ht',
        'total_ttc',
        'tva',
        'date',
        'user_id',
    ];

    protected $casts = [
        'important' => 'array', // Cast JSON to array for easier handling in PHP
        'total_ht' => 'decimal:2',
        'total_ttc' => 'decimal:2',
        'tva' => 'decimal:2',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(BonCommandeItem::class);
    }

}