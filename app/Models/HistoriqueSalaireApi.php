<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueSalaireApi extends Model
{
    use HasFactory;

    protected $table = 'historique_salaires_api';

    protected $fillable = [
        'annee',
        'mois',
        'nombre_employes',
        'montant_total',
        'details_salaires',
        'statut',
        'importe_par',
        'importe_le',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'details_salaires' => 'array',
        'importe_le' => 'datetime',
    ];

    // ========================================
    // Relations
    // ========================================
    
    public function importePar()
    {
        return $this->belongsTo(User::class, 'importe_par');
    }

    // ========================================
    // Accessors
    // ========================================
    
    public function getMoisNomAttribute()
    {
        $mois = [
            1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
            5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
            9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
        ];

        return $mois[$this->mois] ?? '';
    }

    public function getStatutBadgeAttribute()
    {
        $badges = [
            'importe' => '<span class="badge bg-warning">Importé</span>',
            'valide' => '<span class="badge bg-info">Validé</span>',
            'integre' => '<span class="badge bg-success">Intégré</span>',
        ];

        return $badges[$this->statut] ?? '';
    }
}