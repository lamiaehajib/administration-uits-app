<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'recu_ucg_id', 'user_id',
        'montant', 'mode_paiement', 'reference',
        'date_paiement', 'notes'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    // 🔗 Relations
    public function recuUcg()
    {
        return $this->belongsTo(RecuUcg::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 📊 Scopes
    public function scopeEspeces($query)
    {
        return $query->where('mode_paiement', 'especes');
    }

    public function scopeCarte($query)
    {
        return $query->where('mode_paiement', 'carte');
    }

    public function scopeCheque($query)
    {
        return $query->where('mode_paiement', 'cheque');
    }

    public function scopeVirement($query)
    {
        return $query->where('mode_paiement', 'virement');
    }

    public function scopeDuMois($query)
    {
        return $query->whereMonth('date_paiement', now()->month)
                     ->whereYear('date_paiement', now()->year);
    }

    public function scopeDuJour($query)
    {
        return $query->whereDate('date_paiement', now()->toDateString());
    }
}
