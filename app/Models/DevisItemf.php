<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisItemf extends Model
{
    use HasFactory;

    protected $table = 'devis_itemsf';
    protected $fillable = [
        'libele', 'formation', 'prix_unitaire', 'prix_total', 'devis_id','nombre','nombre_de_jours'
    ];
    

    // العلاقة مع نموذج Devis
 // Specify the foreign key for the relationship
 public function devis()
{
    return $this->belongsTo(Devisf::class, 'devis_id');
}



}
