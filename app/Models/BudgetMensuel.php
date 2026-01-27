<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetMensuel extends Model
{
    use HasFactory;

    protected $table = 'budgets_mensuels';

    protected $fillable = [
        'annee',
        'mois',
        'budget_fixes',
        'budget_variables',
        'depense_fixes_realisee',
        'depense_variables_realisee',
        'statut',
        'notes',
        'alerte_depassement',
        'date_alerte',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'budget_fixes' => 'decimal:2',
        'budget_variables' => 'decimal:2',
        'depense_fixes_realisee' => 'decimal:2',
        'depense_variables_realisee' => 'decimal:2',
        'alerte_depassement' => 'boolean',
        'date_alerte' => 'datetime',
    ];

    // ========================================
    // Relations
    // ========================================
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ========================================
    // Scopes
    // ========================================
    
    public function scopePourMois($query, $annee, $mois)
    {
        return $query->where('annee', $annee)->where('mois', $mois);
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
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

    public function getTauxExecutionAttribute()
    {
        if ($this->budget_total == 0) return 0;
        
        return round(($this->depense_totale_realisee / $this->budget_total) * 100, 2);
    }

    public function getIsDepasseAttribute()
    {
        return $this->depense_totale_realisee > $this->budget_total;
    }

    // ========================================
    // Méthodes
    // ========================================
    
    /**
     * Mettre à jour les dépenses réalisées
     */
    public function recalculerDepenses()
    {
        $debut = \Carbon\Carbon::create($this->annee, $this->mois, 1)->startOfMonth();
        $fin = \Carbon\Carbon::create($this->annee, $this->mois, 1)->endOfMonth();

        // Dépenses fixes
        $this->depense_fixes_realisee = DepenseFixe::pourMois($this->annee, $this->mois)
            ->sum('montant_mensuel');

        // Dépenses variables
        $this->depense_variables_realisee = DepenseVariable::pourMois($this->annee, $this->mois)
            ->validee()
            ->sum('montant');

        $this->save();
    }
}