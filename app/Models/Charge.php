<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // ⬅️ AJOUTE CETTE LIGNE

class Charge extends Model
{
    use HasFactory, SoftDeletes;

    // ================================= CONSTANTES ==============================
    const TYPE_FIXE = 'fixe';
    const TYPE_VARIABLE = 'variable';
    
    const MODE_ESPECES = 'especes';
    const MODE_VIREMENT = 'virement';
    const MODE_CHEQUE = 'cheque';
    const MODE_CARTE = 'carte';
    const MODE_AUTRE = 'autre';
    
    const STATUT_PAYE = 'paye';
    const STATUT_IMPAYE = 'impaye';
    const STATUT_PARTIEL = 'partiel';
    
    const FREQ_MENSUEL = 'mensuel';
    const FREQ_TRIMESTRIEL = 'trimestriel';
    const FREQ_ANNUEL = 'annuel';
    const FREQ_UNIQUE = 'unique';

    protected $fillable = [
        'libelle',
        'description',
        'numero_reference',
        'type',
        'charge_category_id',
        'montant',
        'date_charge',
        'date_echeance',
        'mode_paiement',
        'reference_paiement',
        'statut_paiement',
        'montant_paye',
        'fournisseur',
        'fournisseur_telephone',
        'recurrent',
        'frequence',
        'facture_path',
        'notes',
        'user_id',
        'recu_item_id_gift',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'date_charge' => 'date',
        'date_echeance' => 'date',
        'recurrent' => 'boolean',
    ];

    // ================================= RELATIONS ==============================
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ================================= BOOT EVENTS ==============================
    
   protected static function boot()
{
    parent::boot();

    static::creating(function ($charge) {
        // Générer le numéro de référence si vide
        if (empty($charge->numero_reference)) {
            $charge->numero_reference = self::generateNumeroReference();
            \Log::info("📝 Numéro généré dans boot: {$charge->numero_reference}");
        }
        
        // Si montant_paye pas défini, calculer selon statut
        if ($charge->montant_paye === null) {
            $charge->montant_paye = match($charge->statut_paiement) {
                self::STATUT_PAYE => $charge->montant,
                self::STATUT_IMPAYE => 0,
                self::STATUT_PARTIEL => $charge->montant_paye ?? 0,
                default => $charge->montant
            };
        }
    });

    static::updating(function ($charge) {
        // Recalculer statut paiement si montant change
        if ($charge->isDirty('montant_paye') || $charge->isDirty('montant')) {
            $montantPaye = (float) $charge->montant_paye;
            $montant = (float) $charge->montant;
            
            if ($montantPaye >= $montant - 0.01) {
                $charge->statut_paiement = self::STATUT_PAYE;
            } elseif ($montantPaye > 0) {
                $charge->statut_paiement = self::STATUT_PARTIEL;
            } else {
                $charge->statut_paiement = self::STATUT_IMPAYE;
            }
        }
    });
}

    // ================================= ACCESSEURS ==============================
    
    public function getResteAPayerAttribute(): float
    {
        return max(0, $this->montant - $this->montant_paye);
    }

    public function getIsPayeAttribute(): bool
    {
        return $this->statut_paiement === self::STATUT_PAYE;
    }

    public function getIsEnRetardAttribute(): bool
    {
        if (!$this->date_echeance || $this->is_paye) {
            return false;
        }
        return Carbon::now()->isAfter($this->date_echeance);
    }

    // ================================= SCOPES ==============================
    
    public function scopeFixe($query)
    {
        return $query->where('type', self::TYPE_FIXE);
    }

    public function scopeVariable($query)
    {
        return $query->where('type', self::TYPE_VARIABLE);
    }

    public function scopePaye($query)
    {
        return $query->where('statut_paiement', self::STATUT_PAYE);
    }

    public function scopeImpaye($query)
    {
        return $query->where('statut_paiement', self::STATUT_IMPAYE);
    }

    public function scopeDuMois($query, ?int $mois = null, ?int $annee = null)
    {
        $mois = $mois ?? now()->month;
        $annee = $annee ?? now()->year;
        
        return $query->whereMonth('date_charge', $mois)
                     ->whereYear('date_charge', $annee);
    }

    public function scopeEntreDates($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_charge', [$dateDebut, $dateFin]);
    }

    public function scopeRecurrent($query)
    {
        return $query->where('recurrent', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('libelle', 'like', "%{$search}%")
              ->orWhere('numero_reference', 'like', "%{$search}%")
              ->orWhere('fournisseur', 'like', "%{$search}%");
        });
    }

    // ================================= MÉTHODES ==============================
    
   public static function generateNumeroReference(): string
{
    return DB::transaction(function () {
        $year = date('Y');
        $maxAttempts = 5;
        
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            // Récupérer le dernier numéro (inclure soft deleted)
            $lastCharge = self::withTrashed()
                ->whereYear('created_at', $year)
                ->whereNotNull('numero_reference')
                ->where('numero_reference', 'LIKE', "CHG-{$year}-%")
                ->lockForUpdate()
                ->orderByRaw('CAST(SUBSTRING(numero_reference, -4) AS UNSIGNED) DESC')
                ->first();

            // Calculer le prochain numéro
            if ($lastCharge && preg_match('/CHG-\d{4}-(\d{4})/', $lastCharge->numero_reference, $matches)) {
                $number = intval($matches[1]) + 1;
            } else {
                $number = 1;
            }

            $numeroReference = sprintf('CHG-%s-%04d', $year, $number);

            // Vérifier unicité (inclure soft deleted)
            $exists = self::withTrashed()
                ->where('numero_reference', $numeroReference)
                ->exists();

            if (!$exists) {
                \Log::info("✅ Numéro généré: {$numeroReference}");
                return $numeroReference;
            }

            \Log::warning("⚠️ Doublon détecté: {$numeroReference}, tentative {$attempt}");
            
            // Petit délai anti-collision
            if ($attempt < $maxAttempts - 1) {
                usleep(rand(5000, 15000)); // 5-15ms aléatoire
            }
        }

        // Fallback ultime avec timestamp unique
        $fallback = sprintf('CHG-%s-%s', $year, strtoupper(substr(uniqid(), -4)));
        \Log::error("❌ Échec génération numéro, fallback: {$fallback}");
        return $fallback;
    });
}
    /**
     * Marquer comme payé
     */
    public function marquerPayee(float $montant = null): void
    {
        $this->update([
            'montant_paye' => $montant ?? $this->montant,
            'statut_paiement' => self::STATUT_PAYE,
        ]);
    }

    /**
     * Ajouter un paiement partiel
     */
    public function ajouterPaiement(float $montant): void
    {
        $nouveauMontant = $this->montant_paye + $montant;
        
        $this->update([
            'montant_paye' => min($nouveauMontant, $this->montant),
        ]);
    }

    /**
     * Générer la prochaine échéance (pour charges récurrentes)
     */
    public function genererProchaineEcheance(): ?self
    {
        if (!$this->recurrent || $this->frequence === self::FREQ_UNIQUE) {
            return null;
        }

        $nouvelleDate = match($this->frequence) {
            self::FREQ_MENSUEL => $this->date_echeance->addMonth(),
            self::FREQ_TRIMESTRIEL => $this->date_echeance->addMonths(3),
            self::FREQ_ANNUEL => $this->date_echeance->addYear(),
            default => null,
        };

        if (!$nouvelleDate) {
            return null;
        }

        return self::create([
            'libelle' => $this->libelle,
            'description' => $this->description,
            'type' => $this->type,
            'charge_category_id' => $this->charge_category_id,
            'montant' => $this->montant,
            'date_charge' => $nouvelleDate,
            'date_echeance' => $nouvelleDate,
            'mode_paiement' => $this->mode_paiement,
            'fournisseur' => $this->fournisseur,
            'fournisseur_telephone' => $this->fournisseur_telephone,
            'recurrent' => true,
            'frequence' => $this->frequence,
            'user_id' => $this->user_id,
            'statut_paiement' => self::STATUT_IMPAYE,
            'montant_paye' => 0,
        ]);
    }

    // ================================= STATISTIQUES ==============================
    
    /**
     * Total des charges pour une période
     */
    public static function totalPeriode($dateDebut, $dateFin, ?string $type = null): float
    {
        $query = self::entreDates($dateDebut, $dateFin)->paye();
        
        if ($type) {
            $query->where('type', $type);
        }
        
        return (float) $query->sum('montant');
    }

    /**
     * Total des charges du mois
     */
    public static function totalDuMois(?int $mois = null, ?int $annee = null): float
    {
        return (float) self::duMois($mois, $annee)->paye()->sum('montant');
    }

    /**
     * Charges par catégorie
     */
    public static function parCategorie($dateDebut, $dateFin)
    {
        return self::with('category')
            ->entreDates($dateDebut, $dateFin)
            ->paye()
            ->get()
            ->groupBy('charge_category_id')
            ->map(function ($charges) {
                return [
                    'category' => $charges->first()->category?->nom ?? 'Sans catégorie',
                    'total' => $charges->sum('montant'),
                    'count' => $charges->count(),
                ];
            });
    }
}