<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantInfofac extends Model
{
    use HasFactory;

    // تحديد اسم الجدول
    protected $table = 'important_infofac';

    // الحقول القابلة للتعبئة
    protected $fillable = [
        'facturefs_id',
        'info',
    ];

    // العلاقة مع Facturef
    public function facturef()
    {
        return $this->belongsTo(Facturef::class, 'facturefs_id');
    }
}
