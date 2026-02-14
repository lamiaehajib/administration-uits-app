<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paiement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'recu_ucg_id', 'user_id',
        'montant', 'mode_paiement', 'reference',
        'date_paiement', 'notes'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'date',
    ];

    // ✅ CASCADE DELETE - Ki tsupprimé reçu, ims7o paiements
    protected static function boot()
    {
        parent::boot();
        
        // ✅ Ki reçu ims7 (soft delete), ims7o paiements
        static::deleted(function ($paiement) {
            // Hadi optional: logger li tsupprimé
            \Log::info("🗑️ Paiement #{$paiement->id} supprimé (Reçu #{$paiement->recu_ucg_id})");
        });
    }

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