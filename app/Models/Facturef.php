<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturef extends Model
{
    use HasFactory;

    // تحديد اسم الجدول
    protected $table = 'facturefs';

    // الحقول القابلة للتعبئة
    protected $fillable = [
        'facturef_num',
        'date',
        'titre',
        'client',
        'ice',
        'adresse',
        'vide',
        'ref',
        'total_ht',
        'tva',
        'total_ttc',
        'important',
        'user_id',
        'tele',
        'afficher_cachet',
        'currency',
    ];

    // تحديد الحقول التي يتم تخزينها كـ JSON
    protected $casts = [
        'important' => 'array',
    ];

    // العلاقة مع FacturefItem
    public function items()
    {
        return $this->hasMany(FacturefItem::class, 'facturefs_id');
    }

    // العلاقة مع ImportantInfofac
    public function importantInfo()
    {
        return $this->hasMany(ImportantInfofac::class, 'facturefs_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
