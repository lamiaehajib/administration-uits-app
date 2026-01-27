<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RappelPaiement extends Model
{
    use HasFactory;

    protected $table = 'rappels_paiements';

    protected $fillable = [
        'type_source',
        'source_type',
        'source_id',
        'date_echeance',
        'date_rappel',
        'jours_avant',
        'titre',
        'message',
        'montant',
        'statut',
        'destinataires',
        'envoye_le',
        'lu_le',
        'notification_email',
        'notification_app',
        'notification_sms',
    ];

    protected $casts = [
        'date_echeance' => 'date',
        'date_rappel' => 'date',
        'montant' => 'decimal:2',
        'destinataires' => 'array',
        'envoye_le' => 'datetime',
        'lu_le' => 'datetime',
        'notification_email' => 'boolean',
        'notification_app' => 'boolean',
        'notification_sms' => 'boolean',
    ];

    // ========================================
    // Relations
    // ========================================
    
    public function source()
    {
        return $this->morphTo();
    }

    // ========================================
    // Scopes
    // ========================================
    
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAEnvoyer($query)
    {
        return $query->where('statut', 'en_attente')
                    ->where('date_rappel', '<=', now());
    }
}