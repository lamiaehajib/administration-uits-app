<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom_complet',
        'phone',
        'device_type',
        'device_brand',
        'problem_description',
        'date_depot',
        'time_depot',
        'estimated_completion',
        'montant_total',
        'avance',
        'reste',
        'details',
        'status',
    ];

    protected $casts = [
        'date_depot' => 'date',
        'estimated_completion' => 'date',
        'montant_total' => 'decimal:2',
        'avance' => 'decimal:2',
        'reste' => 'decimal:2',
    ];

    // Relation avec User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Calculer le reste automatiquement
    public function calculateReste(): float
    {
        return $this->montant_total - $this->avance;
    }

    // Mutator: calcul automatique du reste
    protected static function booted()
    {
        static::saving(function ($ticket) {
            $ticket->reste = $ticket->montant_total - $ticket->avance;
        });
    }

    // Scopes للفلترة
    public function scopeEnAttente($query)
    {
        return $query->where('status', 'en_attente');
    }

    public function scopeEnCours($query)
    {
        return $query->where('status', 'en_cours');
    }

    public function scopeTermine($query)
    {
        return $query->where('status', 'termine');
    }

    public function scopeLivre($query)
    {
        return $query->where('status', 'livre');
    }
}