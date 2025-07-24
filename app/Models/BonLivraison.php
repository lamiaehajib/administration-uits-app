<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonLivraison extends Model
{
    use HasFactory;

    protected $table = 'bon_livraison';

    protected $fillable = [
        'bon_num',
        'titre',
        'client',
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
        'important' => 'array',
        'date' => 'date',
        'total_ht' => 'decimal:2',
        'total_ttc' => 'decimal:2',
        'tva' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function items()
    {
        return $this->hasMany(BonLivraisonItem::class);
    }
}