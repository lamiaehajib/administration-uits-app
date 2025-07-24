<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marge extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'marge'
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
