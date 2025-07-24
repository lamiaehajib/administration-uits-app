<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportantInfo extends Model
{
    use HasFactory;
    protected $table = 'important_info';

    
    protected $fillable = [
        'devis_id',
        'info',
    ];

    /**
     * تعريف العلاقة بين important_info و devis.
     * تتيح لنا استرجاع الـ devis المرتبط بكل "important_info".
     */
    public function devis()
    {
        return $this->belongsTo(Devis::class); // العلاقة مع جدول devis
    }
}
