<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id', 'recu_ucg_id', 'user_id',
        'type', 'quantite', 'stock_avant', 'stock_apres',
        'reference', 'motif'
    ];

    protected $casts = [
        'quantite' => 'integer',
        'stock_avant' => 'integer',
        'stock_apres' => 'integer',
    ];

    // 🔗 Relations
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function recuUcg()
    {
        return $this->belongsTo(RecuUcg::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 📊 Scopes
    public function scopeEntrees($query)
    {
        return $query->where('type', 'entree');
    }

    public function scopeSorties($query)
    {
        return $query->where('type', 'sortie');
    }

    public function scopeAjustements($query)
    {
        return $query->where('type', 'ajustement');
    }

    public function scopeRetours($query)
    {
        return $query->where('type', 'retour');
    }

    public function scopeDuMois($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}