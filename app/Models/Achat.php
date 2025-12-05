<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes

class Achat extends Model
{
    // Use Traits
    use HasFactory, SoftDeletes; // Zid SoftDeletes

    
    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'produit_id',
        'user_id',
        'fournisseur',
        'numero_bon',
        'quantite',
        'prix_achat',
        'total_achat',
        'date_achat', // New field
        'notes',      // New field
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'date_achat' => 'date', // Ghadi yrodha Carbon instance
        'prix_achat' => 'decimal:2',
        'total_achat' => 'decimal:2',
    ];

    // --- Relations (العلاقات) ---

    /**
     * Get the product that was purchased.
     */
    public function produit()
{
    
    return $this->belongsTo(Produit::class); 
}
    
    /**
     * Get the user who registered the purchase (optional).
     */
    public function user()
    {
        // Kanfترضou an l-Model dial user smitou User
        // Had relation hiya nullable (nullOnDelete)
        return $this->belongsTo(User::class);
    }
}