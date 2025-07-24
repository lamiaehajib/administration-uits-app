<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturefItem extends Model
{
    use HasFactory;

    // تحديد اسم الجدول
    protected $table = 'facturefs_items';

    // الحقول القابلة للتعبئة
    protected $fillable = [
        'facturefs_id',
        'libelle',
        
        'prix_ht',
        'prix_total',
        'nombre_jours',
        'nombre_collaborateurs',
        'duree',
    ];

    // العلاقة مع Facturef
    public function facturef()
    {
        return $this->belongsTo(Facturef::class, 'facturefs_id');
    }
}
