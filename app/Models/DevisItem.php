<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisItem extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'libele', 'quantite', 'prix_unitaire', 'prix_total', 'devis_id',
    ];

    // العلاقة مع نموذج Devis
 // Specify the foreign key for the relationship
 public function devis()
{
    return $this->belongsTo(Devis::class);
}
}